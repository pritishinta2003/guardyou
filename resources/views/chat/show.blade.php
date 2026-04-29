@extends('layouts.app')

@section('title', 'Satellite Uplink // Booking #' . $booking->id)

@push('styles')
<style>
    /* Full-height tactical chat layout */
    .chat-container {
        height: 100vh;
        display: flex;
        flex-direction: column;
        padding-top: 80px; /* navbar */
        background: var(--color-surface);
        max-width: 1000px;
        margin: 0 auto;
        border-left: 1px solid rgba(255, 255, 255, 0.05);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* ===== CHAT HEADER (UPLINK STATUS) ===== */
    .uplink-header {
        padding: 2rem 3rem;
        background: rgba(13, 27, 42, 0.4);
        border-bottom: 1px solid rgba(233, 193, 118, 0.1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .uplink-indicator {
        width: 10px; height: 10px; border-radius: 50%;
        background: #4ade80; box-shadow: 0 0 10px #4ade80;
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }

    .uplink-title { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.3em; color: var(--color-gold); display: block; }
    .peer-name { font-size: 1.5rem; font-weight: 900; text-transform: uppercase; letter-spacing: -0.02em; }

    /* ===== MESSAGE FEED ===== */
    .message-feed {
        flex: 1;
        overflow-y: auto;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        scroll-behavior: smooth;
    }
    /* Custom Scrollbar */
    .message-feed::-webkit-scrollbar { width: 4px; }
    .message-feed::-webkit-scrollbar-thumb { background: rgba(233, 193, 118, 0.1); border-radius: 2px; }

    .msg-group { display: flex; flex-direction: column; max-width: 75%; }
    .msg-group.sent { align-self: flex-end; align-items: flex-end; }
    .msg-group.received { align-self: flex-start; align-items: flex-start; }

    .msg-bubble {
        padding: 1.25rem 1.75rem;
        font-size: 0.95rem;
        line-height: 1.6;
        border-radius: 8px;
        position: relative;
    }
    .msg-group.sent .msg-bubble {
        background: linear-gradient(135deg, var(--color-gold), #c5a059);
        color: #000;
        font-weight: 600;
        border-bottom-right-radius: 2px;
    }
    .msg-group.received .msg-bubble {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: var(--color-on-surface);
        border-bottom-left-radius: 2px;
    }

    .msg-meta {
        font-size: 0.6rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.1em; color: var(--color-on-surface-variant);
        margin-top: 0.5rem;
    }

    /* ===== INPUT TERMINAL ===== */
    .uplink-terminal {
        padding: 2rem 3rem;
        background: rgba(13, 27, 42, 0.6);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }
    .terminal-form { display: flex; gap: 1rem; align-items: flex-end; }
    
    .terminal-input {
        flex: 1;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 4px; padding: 1.2rem 1.5rem;
        color: var(--color-on-surface); font-size: 0.95rem;
        font-family: 'Inter', sans-serif; resize: none;
        outline: none; transition: all 0.3s ease;
    }
    .terminal-input:focus { border-color: var(--color-gold); background: rgba(255, 255, 255, 0.05); }

    .transmit-btn {
        width: 60px; height: 60px; border-radius: 4px;
        background: var(--color-gold); border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s;
    }
    .transmit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(220,20,60,0.2); }
    .transmit-btn svg { width: 24px; height: 24px; fill: #000; }

    /* Typing Protocol */
    .typing-protocol {
        display: none; font-size: 0.6rem; font-weight: 800; color: var(--color-gold);
        text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 1rem;
    }
    .typing-protocol.active { display: block; animation: blink 1s infinite; }
    @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }

</style>
@endpush

@section('content')
<div class="chat-container">

    @php
        $peer = auth()->id() === $booking->user_id
            ? $booking->bodyguard->user
            : $booking->user;
    @endphp

    <!-- HEADER: UPLINK STATUS -->
    <header class="uplink-header">
        <a href="{{ route('bookings.show', $booking) }}" style="color:var(--color-on-surface-variant); text-decoration:none; font-size:1.5rem;">←</a>
        <div class="uplink-indicator"></div>
        <div>
            <span class="uplink-title">Pesan terenskripsi</span>
            <span class="peer-name">{{ $peer->name }}</span>
        </div>
        <div style="margin-left:auto; text-align:right;">
            <span class="uplink-title" style="color:var(--color-on-surface-variant);">Id Order</span>
            <span class="strip-val" style="font-size:1rem; opacity:0.8;">#GY-{{ $booking->id }}</span>
        </div>
    </header>

    <!-- MESSAGE FEED -->
    <main class="message-feed" id="messagesArea">
        @if($messages->isEmpty())
        <div data-empty style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; opacity:0.3;">
            <span style="font-size:4rem; margin-bottom:1rem;">📡</span>
            <span class="uplink-title">Menunggu pesan terkirim</span>
        </div>
        @else
            @foreach($messages as $msg)
            @php $isSent = $msg->sender_id === auth()->id(); @endphp
            <div class="msg-group {{ $isSent ? 'sent' : 'received' }}" data-msg-id="{{ $msg->id }}">
                <div class="msg-bubble">
                    {!! nl2br(e($msg->message)) !!}
                </div>
                <span class="msg-meta">{{ $msg->sender->name }} // {{ $msg->created_at->format('H:i') }}</span>
            </div>
            @endforeach
        @endif

        <div class="typing-protocol" id="typingIndicator">
            Sedang mengetik...
        </div>
    </main>

    <!-- INPUT TERMINAL -->
    <footer class="uplink-terminal">
        <form class="terminal-form" id="chatForm">
            @csrf
            <textarea
                id="chatInput"
                class="terminal-input"
                placeholder="Ketik Pesan"
                rows="1"
                autocomplete="off"
            ></textarea>
            <button type="submit" class="transmit-btn" id="sendBtn">
                <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>
    </footer>

</div>

@push('scripts')
<script>
const BOOKING_ID  = {{ $booking->id }};
const AUTH_ID     = {{ auth()->id() }};
const AUTH_NAME   = @json(auth()->user()->name);
const SEND_URL    = "{{ route('chat.store', $booking) }}";
const POLL_URL    = "{{ route('chat.messages', $booking) }}";
const CSRF_TOKEN  = "{{ csrf_token() }}";

const messagesArea    = document.getElementById('messagesArea');
const chatForm        = document.getElementById('chatForm');
const chatInput       = document.getElementById('chatInput');
const typingIndicator = document.getElementById('typingIndicator');

// Track last rendered message id for polling
let lastMsgId = {{ $messages->last()?->id ?? 0 }};

function scrollToBottom() {
    messagesArea.scrollTop = messagesArea.scrollHeight;
}
scrollToBottom();

function renderMessage(data, isSent) {
    // Remove empty-state placeholder if present
    const placeholder = messagesArea.querySelector('[data-empty]');
    if (placeholder) placeholder.remove();

    const time = new Date(data.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
    const group = document.createElement('div');
    group.className = `msg-group ${isSent ? 'sent' : 'received'}`;
    group.dataset.msgId = data.id;
    group.innerHTML = `
        <div class="msg-bubble">${escapeHtml(data.message).replace(/\n/g, '<br>')}</div>
        <span class="msg-meta">${escapeHtml(data.sender?.name || 'AGENT')} // ${time}</span>
    `;
    messagesArea.insertBefore(group, typingIndicator);
    scrollToBottom();
    if (data.id > lastMsgId) lastMsgId = data.id;
}

function escapeHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

// Auto-resize textarea
chatInput.addEventListener('input', () => {
    chatInput.style.height = 'auto';
    chatInput.style.height = Math.min(chatInput.scrollHeight, 120) + 'px';
});

// Send on Enter
chatInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        chatForm.dispatchEvent(new Event('submit'));
    }
});

chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const text = chatInput.value.trim();
    if (!text) return;

    chatInput.value = '';
    chatInput.style.height = 'auto';

    try {
        const res = await fetch(SEND_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: text }),
        });
        if (!res.ok) throw new Error('Send failed');
        const data = await res.json();
        renderMessage(data, true);
    } catch (err) {
        console.error('Send error:', err);
    }
});

// Real-time via Echo (Reverb), or fall back to polling
if (window.Echo) {
    window.Echo.private(`booking.${BOOKING_ID}`)
        .listen('.message.sent', (data) => {
            if (data.sender_id !== AUTH_ID) {
                typingIndicator.classList.remove('active');
                renderMessage(data, false);
            }
        })
        .listenForWhisper('typing', () => {
            typingIndicator.classList.add('active');
            scrollToBottom();
            clearTimeout(typingIndicator._timer);
            typingIndicator._timer = setTimeout(() => typingIndicator.classList.remove('active'), 3000);
        });

    chatInput.addEventListener('input', () => {
        if (chatInput.value.length > 0)
            window.Echo.private(`booking.${BOOKING_ID}`).whisper('typing', { name: AUTH_NAME });
    });
} else {
    // Polling fallback every 3s when Reverb is not running
    setInterval(async () => {
        try {
            const res = await fetch(`${POLL_URL}?after=${lastMsgId}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
            });
            const msgs = await res.json();
            msgs.forEach(m => {
                if (m.sender_id !== AUTH_ID) renderMessage(m, false);
                else if (m.id > lastMsgId) lastMsgId = m.id;
            });
        } catch (_) {}
    }, 3000);
}
</script>
@endpush
@endsection
