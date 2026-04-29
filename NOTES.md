# GuardYou — Application Fundamentals

Aplikasi marketplace bodyguard berbasis Laravel. Menghubungkan klien yang membutuhkan proteksi personal dengan bodyguard profesional yang terverifikasi.

---

## Stack Teknologi

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12 (PHP) |
| Database | PostgreSQL (Neon / Laravel Cloud) |
| Frontend | Blade + Tailwind CSS + Vite |
| Autentikasi | Laravel Breeze |
| Payment | Midtrans Snap |
| Real-time | Laravel Reverb (WebSocket) + Polling fallback |
| Queue | Database driver |

---

## Arsitektur Sistem

```
User/Bodyguard/Admin
        │
   [Browser]
        │
   [Laravel Router] ── Middleware (Auth, Role, VerifiedBodyguard)
        │
   [Controller] ── [Service: MidtransService]
        │               │
   [Model]          [Midtrans API]
        │
   [PostgreSQL (Neon)]
        │
   [Broadcasting: Reverb] ── [WebSocket Client]
```

---

## Roles & Hak Akses

| Role | Kemampuan |
|---|---|
| `user` | Browse bodyguard, buat booking, bayar, chat |
| `bodyguard` | Lihat assignment, update status, chat, edit profil |
| `admin` | Kelola semua user, verifikasi bodyguard, lihat semua booking |

---

## Alur Utama Aplikasi

### 1. Booking Flow

```
User browse bodyguard
    → Pilih bodyguard → Isi tanggal → Klik "Authorize Deployment"
    → Booking dibuat (status: pending)
    → Redirect ke halaman bayar (Midtrans Snap)
    → User bayar → Midtrans kirim webhook
    → Webhook diverifikasi → status: paid
    → Bodyguard ubah status → active → completed
```

### 2. Conflict Prevention

Saat booking dibuat, sistem menggunakan **pessimistic lock** (`lockForUpdate()`) untuk mengecek apakah bodyguard sudah dibooking di rentang tanggal yang sama dengan status `paid` atau `active`. Jika ada konflik, booking ditolak.

### 3. Payment Flow (Midtrans)

```
BookingController@pay
    → MidtransService@createSnapTransaction
    → Generate order_id: GY-{booking_id}-{random}-{timestamp}
    → Kirim ke Midtrans API → Dapat payment_url
    → Redirect user ke Midtrans Snap

Midtrans POST /webhook/midtrans
    → Verifikasi signature (SHA-512)
    → Resolve status (capture/settlement → paid, cancel/expire → cancelled)
    → Pessimistic lock → Update booking status
```

### 4. Chat Flow

```
User/Bodyguard buka chat
    → Load pesan dari DB
    → Kirim pesan → ChatController@store → simpan ke DB
    → broadcast(MessageSent) → Reverb WebSocket
    → Jika Reverb offline → polling setiap 3 detik ke GET /chat/{booking}/messages?after={lastId}
```

---

## Database Schema

```
users
├── id, name, email, password
├── role: enum(admin, user, bodyguard)
├── phone_number, avatar
└── email_verified_at

bodyguards
├── id, user_id (FK → users)
├── ktp_number (unique)
├── dob, height, weight, experience_years
├── daily_rate (decimal)
└── is_verified (boolean)

bookings
├── id, user_id (FK → users), bodyguard_id (FK → bodyguards)
├── start_date, end_date
├── total_price (decimal)
├── status: enum(pending, paid, active, completed, cancelled)
└── payment_url

messages
├── id, booking_id (FK → bookings)
├── sender_id (FK → users)
└── message (text)
```

---

## Model Relationships

```
User
 ├── hasOne → Bodyguard
 ├── hasMany → Booking (sebagai klien)
 └── hasMany → Message (sebagai pengirim)

Bodyguard
 ├── belongsTo → User
 └── hasMany → Booking

Booking
 ├── belongsTo → User
 ├── belongsTo → Bodyguard
 └── hasMany → Message

Message
 ├── belongsTo → Booking
 └── belongsTo → User (sender)
```

---

## Routing Overview

```
GET  /                          → Landing page
GET  /bodyguards                → Daftar bodyguard (publik)
GET  /bodyguards/{id}           → Profil bodyguard

GET  /dashboard                 → Dashboard (redirect by role)

# User only
GET  /bookings/create/{bodyguard}  → Form booking
POST /bookings/{bodyguard}         → Simpan booking
GET  /bookings/{booking}           → Detail booking
POST /bookings/{booking}/pay       → Bayar ulang
POST /bookings/{booking}/cancel    → Batalkan

# Bodyguard only
PATCH /bookings/{booking}/status   → Update status misi
GET   /bodyguard/profile/edit      → Edit profil

# Shared (user + bodyguard)
GET  /chat/{booking}               → Halaman chat
POST /chat/{booking}/messages      → Kirim pesan
GET  /chat/{booking}/messages      → Poll pesan baru

# Admin
GET   /admin/dashboard
GET   /admin/bodyguards
PATCH /admin/bodyguards/{id}/verify
GET   /admin/users
GET   /admin/bookings

# Webhook
POST /webhook/midtrans
```

---

## Middleware

| Middleware | Fungsi |
|---|---|
| `auth` | Wajib login |
| `verified` | Email harus terverifikasi |
| `role:user` | Hanya role user |
| `role:bodyguard` | Hanya role bodyguard |
| `role:admin` | Hanya role admin |
| `verified.bodyguard` | Bodyguard harus sudah diverifikasi admin |

---

## Broadcasting & Real-time

- Channel: `private booking.{booking_id}`
- Event: `MessageSent` (broadcasts `.message.sent`)
- Auth: hanya user/bodyguard yang terlibat dalam booking
- Fallback: polling HTTP setiap 3 detik jika Reverb tidak aktif

---

## Fitur Keamanan

- **CSRF Protection** — semua form POST dilindungi token
- **Signature Verification** — webhook Midtrans diverifikasi dengan SHA-512
- **Pessimistic Locking** — mencegah race condition pada booking & webhook
- **Rate Limiting** — booking: 5/mnt, chat: 30/mnt, payment: 5/mnt
- **Role Middleware** — akses dikontrol di level route
- **XSS Prevention** — semua output di-escape (`e()`, `escapeHtml()`)

---

## Akun Default

| Role | Email | Password |
|---|---|---|
| Super Admin | superadmin@guardyou.com | superadmin123 |
| Admin | admin@guardyou.com | admin123 |
| User Demo | user@guardyou.com | user1234 |

---

## Konfigurasi Penting

### Neon PostgreSQL (SNI Workaround)
`AppServiceProvider` mendaftarkan custom `PostgresConnector` untuk menambahkan `;options=endpoint={NEON_ENDPOINT}` ke DSN. Ini diperlukan karena libpq lama (XAMPP) tidak support SNI yang dibutuhkan Neon.

### Booking Expiry
Command `bookings:cancel-expired` membatalkan booking berstatus `pending` yang sudah lebih dari 2 jam. Dijadwalkan via Laravel Scheduler.

### Storage
Avatar bodyguard disimpan di `storage/app/public/avatars/`. Jalankan `php artisan storage:link` untuk akses publik.

---

## Penjelasan Kode untuk Presentasi

### 1. Sistem Role & Middleware

Setiap user memiliki kolom `role` di tabel `users` dengan nilai `admin`, `user`, atau `bodyguard`. Middleware `RoleMiddleware` membaca role ini dan memblokir akses jika tidak sesuai.

```php
// app/Http/Middleware/RoleMiddleware.php
public function handle(Request $request, Closure $next, string $role): Response
{
    if ($request->user()->role !== $role) {
        abort(403);
    }
    return $next($request);
}
```

Di route, middleware dipasang seperti ini:
```php
// routes/web.php
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::post('/bookings/{bodyguard}', [BookingController::class, 'store']);
});
```

**Penjelasan ke dosen:** "Kami menggunakan role-based access control. Setiap route dilindungi middleware yang mengecek role user sebelum request diproses. Jika role tidak cocok, sistem langsung mengembalikan error 403 Forbidden."

---

### 2. Proses Booking dengan Conflict Detection

Saat user membuat booking, sistem mengecek apakah bodyguard sudah dibooking di tanggal yang sama menggunakan **pessimistic locking** untuk mencegah race condition (dua user booking secara bersamaan).

```php
// app/Http/Controllers/BookingController.php
public function store(Request $request, Bodyguard $bodyguard)
{
    // Validasi input tanggal
    $validated = $request->validate([
        'start_date' => ['required', 'date', 'after_or_equal:today'],
        'end_date'   => ['required', 'date', 'after:start_date'],
    ]);

    // Kunci baris database agar tidak ada booking ganda
    $conflict = Booking::where('bodyguard_id', $bodyguard->id)
        ->whereIn('status', ['paid', 'active'])
        ->where('start_date', '<=', $validated['end_date'])
        ->where('end_date', '>=', $validated['start_date'])
        ->lockForUpdate()  // ← pessimistic lock
        ->exists();

    if ($conflict) {
        return back()->withErrors(['date' => 'Bodyguard sudah dibooking pada tanggal tersebut.']);
    }

    // Hitung total harga
    $days  = Carbon::parse($validated['start_date'])->diffInDays($validated['end_date']);
    $total = $days * $bodyguard->daily_rate;

    $booking = Booking::create([...]);
}
```

**Penjelasan ke dosen:** "Kami menggunakan `lockForUpdate()` dari Laravel yang menghasilkan query SQL `SELECT ... FOR UPDATE`. Ini memastikan tidak ada dua transaksi database yang bisa membuat booking untuk bodyguard yang sama di waktu yang sama, mencegah overbooking."

---

### 3. Integrasi Payment Gateway (Midtrans)

Midtrans Snap digunakan untuk memproses pembayaran. Aplikasi mengirim data booking ke Midtrans API, lalu Midtrans memberikan URL pembayaran yang bisa dibuka user.

```php
// app/Services/MidtransService.php
public function createSnapTransaction(Booking $booking): array
{
    // Buat order ID unik: GY-{id}-{random}-{timestamp}
    $orderId = 'GY-' . $booking->id . '-' . Str::random(8) . '-' . time();

    $payload = [
        'transaction_details' => [
            'order_id'     => $orderId,
            'gross_amount' => (int) $booking->total_price,
        ],
        'customer_details' => [
            'first_name' => $booking->user->name,
            'email'      => $booking->user->email,
        ],
    ];

    // Kirim ke Midtrans API dengan Basic Auth
    $response = Http::withBasicAuth($this->serverKey, '')->post($this->snapUrl, $payload);

    // Simpan URL pembayaran ke booking
    $booking->update(['payment_url' => $response['redirect_url']]);

    return $response->json();
}
```

Setelah user bayar, Midtrans mengirim notifikasi ke webhook:

```php
// app/Http/Controllers/PaymentWebhookController.php
public function handle(Request $request)
{
    // 1. Verifikasi bahwa notifikasi benar dari Midtrans
    $isValid = $this->midtrans->verifySignature($request->all());
    if (!$isValid) abort(403);

    // 2. Ambil booking dari order_id (format: GY-{id}-...)
    $bookingId = explode('-', $request->order_id)[1];
    $booking   = Booking::findOrFail($bookingId);

    // 3. Update status booking
    DB::transaction(function () use ($booking, $request) {
        $booking->lockForUpdate()->first();
        $newStatus = $this->midtrans->resolveBookingStatus($request->all());
        $booking->update(['status' => $newStatus]);
    });
}
```

**Penjelasan ke dosen:** "Integrasi payment menggunakan pola Service Layer. `MidtransService` bertugas berkomunikasi dengan API Midtrans, sehingga controller tidak perlu tahu detail teknis pembayaran. Webhook diverifikasi dengan signature SHA-512 untuk memastikan notifikasi tidak dipalsukan."

---

### 4. Real-time Chat dengan WebSocket

Chat menggunakan Laravel Reverb (WebSocket server). Ketika pesan dikirim, sistem mem-broadcast event ke channel privat. Jika Reverb tidak aktif, ada fallback polling HTTP.

```php
// app/Http/Controllers/ChatController.php
public function store(Request $request, Booking $booking)
{
    // Simpan pesan ke database
    $message = Message::create([
        'booking_id' => $booking->id,
        'sender_id'  => auth()->id(),
        'message'    => $request->message,
    ]);

    // Broadcast ke WebSocket channel (graceful fallback jika Reverb offline)
    try {
        broadcast(new MessageSent($message))->toOthers();
    } catch (\Exception $e) {
        // Pesan tetap tersimpan, polling akan mengambilnya
    }

    return response()->json($message, 201);
}
```

```php
// app/Events/MessageSent.php — mendefinisikan channel & data yang di-broadcast
public function broadcastOn(): array
{
    return [new PrivateChannel('booking.' . $this->message->booking_id)];
}

public function broadcastWith(): array
{
    return [
        'id'         => $this->message->id,
        'sender_id'  => $this->message->sender_id,
        'message'    => $this->message->message,
        'created_at' => $this->message->created_at->toIso8601String(),
    ];
}
```

Di sisi client (JavaScript), pesan diterima real-time atau via polling:
```javascript
// resources/views/chat/show.blade.php
if (window.Echo) {
    // Real-time via WebSocket
    window.Echo.private(`booking.${BOOKING_ID}`)
        .listen('.message.sent', (data) => renderMessage(data, false));
} else {
    // Fallback: polling setiap 3 detik
    setInterval(async () => {
        const res  = await fetch(`${POLL_URL}?after=${lastMsgId}`);
        const msgs = await res.json();
        msgs.forEach(m => renderMessage(m, false));
    }, 3000);
}
```

**Penjelasan ke dosen:** "Sistem chat menggunakan arsitektur event-driven. Ketika pesan dikirim, Laravel men-dispatch event `MessageSent` yang di-broadcast melalui WebSocket ke semua client yang terhubung di channel tersebut. Channel bersifat private, hanya user dan bodyguard yang terlibat dalam booking tersebut yang bisa mengaksesnya. Ada juga fallback polling untuk kondisi ketika WebSocket server tidak tersedia."

---

### 5. Model Eloquent & Relasi Database

Laravel Eloquent ORM memudahkan interaksi dengan database menggunakan objek PHP, bukan query SQL mentah.

```php
// app/Models/Booking.php
class Booking extends Model
{
    protected $fillable = ['user_id', 'bodyguard_id', 'start_date', 'end_date', 'total_price', 'status', 'payment_url'];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'total_price' => 'decimal:2',
    ];

    // Relasi: booking milik seorang user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: booking ditangani satu bodyguard
    public function bodyguard(): BelongsTo
    {
        return $this->belongsTo(Bodyguard::class);
    }

    // Relasi: booking memiliki banyak pesan chat
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
```

Dengan relasi ini, data bisa diakses secara intuitif:
```php
$booking->user->name;           // Nama user yang booking
$booking->bodyguard->daily_rate; // Tarif harian bodyguard
$booking->messages()->count();   // Jumlah pesan di chat
```

**Penjelasan ke dosen:** "Kami menggunakan Eloquent ORM dengan definisi relasi yang jelas. `belongsTo` berarti tabel booking menyimpan foreign key ke tabel lain, sedangkan `hasMany` berarti tabel lain yang menyimpan foreign key ke booking. Ini mengikuti prinsip normalisasi database dan membuat kode lebih mudah dibaca."

---

### 6. Autentikasi dengan Laravel Breeze

Autentikasi menggunakan Laravel Breeze yang menyediakan fitur lengkap: register, login, verifikasi email, reset password.

```php
// app/Http/Controllers/Auth/RegisteredUserController.php
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'email', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role'     => ['required', 'in:user,bodyguard'],
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),  // Password di-hash bcrypt
        'role'     => $request->role,
    ]);

    // Kirim email verifikasi
    event(new Registered($user));
    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
}
```

**Penjelasan ke dosen:** "Password tidak pernah disimpan dalam bentuk plain text. Laravel menggunakan algoritma bcrypt untuk hashing. Selain itu, sistem memerlukan verifikasi email sebelum user bisa menggunakan fitur utama aplikasi, ini untuk memastikan akun yang dibuat adalah valid."

---

### 7. Admin Dashboard & Verifikasi Bodyguard

Admin dapat memverifikasi bodyguard setelah mengecek identitas (KTP). Hanya bodyguard yang terverifikasi yang muncul di katalog publik.

```php
// app/Http/Controllers/AdminController.php
public function verify(Bodyguard $bodyguard): RedirectResponse
{
    // Toggle: jika verified → unverify, jika belum → verify
    $bodyguard->update([
        'is_verified' => !$bodyguard->is_verified,
    ]);

    return back()->with('success', 'Status verifikasi bodyguard diperbarui.');
}

public function dashboard(): View
{
    return view('admin.dashboard', [
        'totalUsers'           => User::where('role', 'user')->count(),
        'totalBodyguards'      => Bodyguard::count(),
        'pendingVerifications' => Bodyguard::where('is_verified', false)->count(),
        'totalRevenue'         => Booking::where('status', 'completed')->sum('total_price'),
    ]);
}
```

**Penjelasan ke dosen:** "Sistem verifikasi bodyguard adalah fitur kunci untuk menjaga kepercayaan platform. Admin berperan sebagai gatekeeper yang memverifikasi identitas bodyguard sebelum mereka bisa menerima booking. Ini diimplementasikan dengan kolom boolean `is_verified` yang hanya bisa diubah oleh admin."

---

### 8. Scheduled Command (Otomatisasi)

Booking yang tidak dibayar dalam 2 jam otomatis dibatalkan oleh scheduled command.

```php
// app/Console/Commands/CancelExpiredBookings.php
public function handle(): void
{
    $cancelled = Booking::where('status', 'pending')
        ->where('created_at', '<', now()->subHours(2))
        ->update(['status' => 'cancelled']);

    $this->info("Cancelled {$cancelled} expired bookings.");
}
```

**Penjelasan ke dosen:** "Kami mengimplementasikan otomatisasi menggunakan Laravel Scheduler. Command ini dijalankan secara berkala oleh cron job di server untuk membatalkan booking yang kedaluwarsa, menjaga ketersediaan bodyguard tetap akurat tanpa intervensi manual."
