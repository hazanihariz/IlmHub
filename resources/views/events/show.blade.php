@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- Back Button -->
            <a href="{{ route('home') }}" class="btn-back mb-4">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Back to Events
            </a>

            <!-- Main Event Card -->
            <div class="event-detail-card">
                <!-- Hero Header with Gradient -->
                <div class="event-hero-header">
                    <div class="event-hero-overlay"></div>
                    <div class="event-hero-content">
                        <span class="event-category-badge">
                            {{ $event->category->name }}
                        </span>
                        <h1 class="event-hero-title">{{ $event->title }}</h1>
                        <div class="event-meta-row">
                            <span class="event-meta-item">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, h:i A') }}
                            </span>
                            <span class="event-meta-item">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                </svg>
                                {{ $event->location }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="event-content-wrapper">
                    <div class="row g-4">
                        <!-- Left Column: Details -->
                        <div class="col-lg-8">
                            <!-- Description -->
                            <div class="content-section">
                                <h3 class="section-title">
                                    <span class="title-icon">📖</span>
                                    About This Event
                                </h3>
                                <p class="event-description">{{ $event->description }}</p>
                            </div>

                            <!-- Event Poster -->
                            @if($event->image_path)
                                @php
                                    // Handle both old storage paths (events/...) and new public paths
                                    $imageUrl = $event->image_path;
                                    // If path starts with 'events/', check if it exists in public first, otherwise use storage
                                    if (strpos($imageUrl, 'events/') === 0) {
                                        $publicPath = public_path($imageUrl);
                                        if (file_exists($publicPath)) {
                                            $imageUrl = asset($imageUrl);
                                        } else {
                                            // Fallback to storage path (use asset('storage/...') to build URL)
                                            $imageUrl = asset('storage/' . ltrim($imageUrl, '/'));
                                        }
                                    } else {
                                        // Legacy storage path
                                        $imageUrl = asset('storage/' . ltrim($imageUrl, '/'));
                                    }
                                @endphp
                                <div class="content-section">
                                    <h3 class="section-title">
                                        <span class="title-icon">🖼️</span>
                                        Event Poster
                                    </h3>
                                    <div class="event-poster-container">
                                        <img
                                            src="{{ $imageUrl }}"
                                            alt="{{ $event->title }} poster"
                                            class="event-poster-img"
                                            onerror="this.onerror=null; this.parentElement.style.display='none';"
                                        >
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Event Details Grid -->
                            <div class="content-section">
                                <h3 class="section-title">
                                    <span class="title-icon">ℹ️</span>
                                    Event Details
                                </h3>
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <div class="detail-icon">📍</div>
                                        <div class="detail-content">
                                            <span class="detail-label">Location</span>
                                            <span class="detail-value">{{ $event->location }}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-icon">📅</div>
                                        <div class="detail-content">
                                            <span class="detail-label">Date & Time</span>
                                            <span class="detail-value">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, h:i A') }}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-icon">👥</div>
                                        <div class="detail-content">
                                            <span class="detail-label">Gender Policy</span>
                                            <span class="detail-badge">{{ ucfirst(str_replace('_', ' ', $event->gender_policy)) }}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-icon">🪑</div>
                                        <div class="detail-content">
                                            <span class="detail-label">Capacity</span>
                                            <span class="detail-value">{{ $event->capacity }} Seats</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Booking Card -->
                        <div class="col-lg-4">
                            <div class="booking-card">
                                <div class="booking-card-icon">🎫</div>
                                <h4 class="booking-card-title">Reserve Your Spot</h4>
                                <div class="booking-price">FREE</div>
                                <p class="booking-subtitle">Secure your seat before they're all taken!</p>
                                
                                @auth
                                    @php
                                        $hasBooked = Auth::user()->bookings->contains('event_id', $event->id);
                                    @endphp

                                    @if($hasBooked)
                                        <button class="btn-booking btn-booked" disabled>
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                            </svg>
                                            Already Booked
                                        </button>
                                        <small class="booking-note">View details in "My Bookings"</small>

                                    @elseif($event->capacity <= 0) 
                                        <button class="btn-booking btn-full" disabled>
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                            Fully Booked
                                        </button>

                                    @else
                                        <form action="{{ route('bookings.store', $event->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-booking btn-primary" onclick="return confirm('Are you sure you want to book a seat?')">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                                </svg>
                                                Confirm Booking
                                            </button>
                                        </form>
                                    @endif

                                @else
                                    <a href="{{ route('login') }}" class="btn-booking btn-secondary">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                        </svg>
                                        Login to Book
                                    </a>
                                @endauth

                                <div class="booking-features">
                                    <div class="feature-item">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                            <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5z"/>
                                        </svg>
                                        Instant confirmation
                                    </div>
                                    <div class="feature-item">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 1a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a6 6 0 1 1 12 0v6a2.5 2.5 0 0 1-2.5 2.5H9.366a1 1 0 0 1-.866.5h-1a1 1 0 1 1 0-2h1a1 1 0 0 1 .866.5H11.5A1.5 1.5 0 0 0 13 12h-1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1V6a5 5 0 0 0-5-5z"/>
                                        </svg>
                                        24/7 Support available
                                    </div>
                                    <div class="feature-item">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                        </svg>
                                        Easy cancellation
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

<style>
    /* ===== BACK BUTTON ===== */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        color: var(--ilm-text-main);
        border: 2px solid rgba(213, 227, 214, 0.6);
        border-radius: var(--ilm-radius-pill);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .btn-back:hover {
        background: var(--ilm-primary);
        color: white;
        transform: translateX(-5px);
        box-shadow: 0 8px 24px rgba(61, 123, 74, 0.3);
        border-color: var(--ilm-primary);
    }

    /* ===== MAIN EVENT CARD ===== */
    .event-detail-card {
        background: white;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== HERO HEADER ===== */
    .event-hero-header {
        position: relative;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        padding: 4rem 3rem;
        overflow: hidden;
    }

    .event-hero-overlay {
        position: absolute;
        inset: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
        animation: shimmerMove 10s ease infinite;
    }

    @keyframes shimmerMove {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }

    .event-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .event-category-badge {
        display: inline-block;
        padding: 0.6rem 1.5rem;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: var(--ilm-radius-pill);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .event-hero-title {
        font-size: 3rem;
        font-weight: 900;
        color: #ffffff;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .event-meta-row {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .event-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* ===== CONTENT WRAPPER ===== */
    .event-content-wrapper {
        padding: 3rem;
    }

    .content-section {
        margin-bottom: 3rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--ilm-primary-deep);
        margin-bottom: 1.5rem;
    }

    .title-icon {
        font-size: 1.8rem;
    }

    .event-description {
        font-size: 1.15rem;
        line-height: 1.8;
        color: var(--ilm-text-main);
    }

    /* ===== EVENT POSTER ===== */
    .event-poster-container {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        transition: all 0.4s ease;
    }

    .event-poster-container:hover {
        transform: scale(1.02);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
    }

    .event-poster-img {
        max-width: 100%;
        max-height: 70vh;
        width: auto;
        height: auto;
        display: block;
        margin: 0 auto;
    }

    /* ===== DETAILS GRID ===== */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 20px;
        border: 1px solid rgba(213, 227, 214, 0.5);
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        border-color: var(--ilm-primary);
    }

    .detail-icon {
        font-size: 2rem;
        flex-shrink: 0;
    }

    .detail-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .detail-label {
        font-size: 0.85rem;
        color: var(--ilm-text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .detail-value {
        font-size: 1.1rem;
        color: var(--ilm-text-main);
        font-weight: 700;
    }

    .detail-badge {
        display: inline-block;
        padding: 0.4rem 0.9rem;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-radius: var(--ilm-radius-pill);
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* ===== BOOKING CARD ===== */
    .booking-card {
        position: sticky;
        top: 100px;
        padding: 2.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #f7faf7 100%);
        border-radius: 28px;
        border: 2px solid rgba(213, 227, 214, 0.6);
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
        text-align: center;
        animation: fadeInRight 0.6s ease 0.3s both;
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .booking-card-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        animation: bounce 2s ease infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .booking-card-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--ilm-primary-deep);
        margin-bottom: 1rem;
    }

    .booking-price {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--ilm-primary), var(--ilm-primary-deep));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .booking-subtitle {
        color: var(--ilm-text-muted);
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }

    /* ===== BOOKING BUTTONS ===== */
    .btn-booking {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        width: 100%;
        padding: 1.2rem 2rem;
        border: none;
        border-radius: var(--ilm-radius-pill);
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        margin-bottom: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--ilm-primary) 0%, var(--ilm-primary-deep) 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(61, 123, 74, 0.35);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 36px rgba(61, 123, 74, 0.45);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(100, 116, 139, 0.35);
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 36px rgba(100, 116, 139, 0.45);
    }

    .btn-booked {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        cursor: not-allowed;
        box-shadow: 0 8px 24px rgba(34, 197, 94, 0.3);
    }

    .btn-full {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        cursor: not-allowed;
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
    }

    .booking-note {
        display: block;
        color: var(--ilm-text-muted);
        font-size: 0.85rem;
        margin-top: -0.5rem;
        margin-bottom: 1rem;
    }

    /* ===== BOOKING FEATURES ===== */
    .booking-features {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid rgba(213, 227, 214, 0.5);
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0;
        color: var(--ilm-text-main);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .feature-item svg {
        color: var(--ilm-primary);
        flex-shrink: 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 991px) {
        .event-hero-title {
            font-size: 2.2rem;
        }

        .event-content-wrapper {
            padding: 2rem 1.5rem;
        }

        .booking-card {
            position: static;
            margin-top: 2rem;
        }
    }

    @media (max-width: 767px) {
        .event-hero-header {
            padding: 3rem 1.5rem;
        }

        .event-hero-title {
            font-size: 1.8rem;
        }

        .event-meta-row {
            flex-direction: column;
            gap: 1rem;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }
    }
</style>