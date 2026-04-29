<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int         $id
 * @property int         $user_id
 * @property int         $bodyguard_id
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property string      $alamat
 * @property string      $total_price
 * @property string      $status
 * @property string|null $payment_url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User      $user
 * @property-read Bodyguard $bodyguard
 */
class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'bodyguard_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'payment_url',
        'alamat',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bodyguard(): BelongsTo
    {
        return $this->belongsTo(Bodyguard::class);
    }
    
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'total_price' => 'decimal:2',
    ];
}
