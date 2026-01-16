@extends('layouts.app')

@section('content')

@php
    $now = \Carbon\Carbon::now();

    $upcomingHighlight = $events
        ->filter(function ($event) use ($now) {
            return \Carbon\Carbon::parse($event->event_date) >= $now;
        })
        ->sortBy('event_date')
        ->first();

    $categoryList = $events
        ->pluck('category')
        ->filter()
        ->unique('id')
        ->values();
@endphp

<div class="ilm-hero card border-0 rounded-4 mb-5 overflow-hidden shadow-lg">
    <div class="row g-0 align-items-center">
        <div class="col-lg-7">
            <div class="p-4 p-md-5">
                <span class="badge rounded-pill text-bg-light text-success mb-3 px-3 py-2">
                    Nearby circles • Weekend halaqahs • Youth programs
                </span>
                <h1 class="fw-bold mb-3" style="color: #0f2a1b;">
                    Find Islamic Knowledge Near You
                </h1>
                <p class="fs-5 mb-4 text-muted" style="max-width: 34rem;">
                    Discover local halaqahs, seminars, and community events to spiritually recharge.
                </p>

                <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3">
                    <button class="btn ilm-cta-primary btn-lg w-100 w-md-auto" type="button" onclick="document.getElementById('events-section')?.scrollIntoView({ behavior: 'smooth' })">
                        Browse Events
                    </button>
                    <a href="{{ route('events.create') }}" class="btn ilm-cta-outline btn-lg w-100 w-md-auto">
                        Host an Event
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
            <div class="h-100 position-relative" style="background: radial-gradient(circle at 10% 0%, #fefce8 0, #dcfce7 40%, #14532d 100%);">
                <div class="position-absolute top-50 start-50 translate-middle w-75">
                    <div class="bg-white bg-opacity-90 rounded-4 shadow-lg p-3 mb-3">
                        @if($upcomingHighlight)
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge rounded-pill text-bg-success">
                                    {{ optional($upcomingHighlight->category)->name ?? 'Upcoming Event' }}
                                </span>
                                <span class="small text-muted">
                                    {{ \Carbon\Carbon::parse($upcomingHighlight->event_date)->diffForHumans(null, true) }} away
                                </span>
                            </div>
                            <h6 class="mb-1">
                                {{ $upcomingHighlight->title }}
                            </h6>
                            <p class="small mb-0 text-muted">
                                {{ $upcomingHighlight->location }} •
                                {{ \Carbon\Carbon::parse($upcomingHighlight->event_date)->format('d M Y, h:i A') }}
                            </p>
                        @else
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge rounded-pill text-bg-success">This Week</span>
                                <span class="small text-muted">Stay tuned</span>
                            </div>
                            <h6 class="mb-1">Your next circle will appear here</h6>
                            <p class="small mb-0 text-muted">Create an event to highlight it on the homepage.</p>
                        @endif
                    </div>
                    <div class="bg-white bg-opacity-85 rounded-4 shadow p-3 ms-auto" style="max-width: 260px;">
                        <p class="small mb-1 text-muted text-uppercase fw-semibold">This week</p>
                        <p class="small mb-0 text-muted">
                            Explore curated events for <span class="fw-semibold">Fiqh</span>, <span class="fw-semibold">Sirah</span>, youth programs, and more.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($categoryList->isNotEmpty())
    <div class="ilm-category-strip py-3 px-3 px-md-4 mb-4 rounded-4" style="background-color: rgba(255,255,255,0.78); box-shadow: var(--ilm-shadow-soft);">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <button type="button"
                    class="ilm-category-pill active"
                    data-category-filter="all">
                <span class="ilm-category-icon">
                    <span class="fw-bold">All</span>
                </span>
                <span class="ilm-category-label">All Events</span>
            </button>

            @foreach($categoryList as $category)
                <button type="button"
                        class="ilm-category-pill"
                        data-category-filter="{{ $category->id }}">
                    <span class="ilm-category-icon">
                        <span class="fw-bold">
                            {{ mb_substr($category->name, 0, 1) }}
                        </span>
                    </span>
                    <span class="ilm-category-label">
                        {{ $category->name }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3" id="events-section">
    <div>
        <h3 class="mb-1 fw-bold" style="color:#123125;">Suggested Events</h3>
        <p class="text-muted small mb-0">
            Browse upcoming gatherings tailored to your interests and schedule.
        </p>
    </div>
</div>

<div class="row g-4">
    @forelse($events as $event)
        <div class="col-md-4 col-sm-6" data-category-id="{{ optional($event->category)->id }}">
            <div class="ilm-event-card h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="ilm-event-avatar">
                            <span>{{ mb_substr(optional($event->category)->name ?? 'E', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="small mb-0 text-muted">Event</p>
                            <p class="fw-semibold mb-0" style="font-size: 0.9rem;">
                                {{ optional($event->category)->name ?? 'General' }}
                            </p>
                        </div>
                    </div>
                    <span class="ilm-card-menu-dot"></span>
                </div>

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
                    <div class="ilm-event-media mb-3">
                        <img 
                            src="{{ $imageUrl }}" 
                            class="img-fluid rounded-3 ilm-event-poster-img" 
                            alt="{{ $event->title }} poster"
                            onerror="this.onerror=null; this.parentElement.classList.add('ilm-event-media--placeholder'); this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                        <div class="ilm-event-media--placeholder" style="display: none;">
                            <div class="placeholder-icon large"></div>
                            <div class="placeholder-icon"></div>
                            <div class="placeholder-icon small"></div>
                        </div>
                    </div>
                @else
                    <div class="ilm-event-media ilm-event-media--placeholder mb-3">
                        <div class="placeholder-icon large"></div>
                        <div class="placeholder-icon"></div>
                        <div class="placeholder-icon small"></div>
                    </div>
                @endif

                <div class="mb-2">
                    <p class="small text-muted mb-1">
                        {{ $event->location }}
                    </p>
                    <h5 class="mb-1" style="font-size: 1.05rem;">
                        {{ $event->title }}
                    </h5>
                    <p class="small text-muted mb-2">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, h:i A') }}
                    </p>
                    <p class="small text-muted ilm-description-line mb-0">
                        {{ $event->description }}
                    </p>
                </div>

                <div class="mt-auto pt-3 d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        👥 {{ ucfirst(str_replace('_', ' ', $event->gender_policy)) }}<br>
                        🪑 {{ $event->capacity }} seats
                    </div>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm ilm-cta-outline px-3">
                            View Details
                        </a>
                        @auth
                            <form action="{{ route('bookings.store', $event->id) }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-sm ilm-cta-primary w-100">
                                    Reserve
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm ilm-cta-primary px-3">
                                Reserve
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">No upcoming events found.</h4>
            <p>Check back later or <a href="{{ route('events.create') }}">create one</a>!</p>
        </div>
    @endforelse
</div>

<style>
    /* ===== HERO SECTION - DRAMATIC REDESIGN ===== */
    .ilm-hero {
        background: linear-gradient(135deg, #0f2027 0%, #203a43 30%, #2c5364 100%);
        position: relative;
        overflow: hidden;
        min-height: 450px;
    }

    /* Animated gradient overlay */
    .ilm-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(250, 204, 21, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(163, 230, 53, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 40% 20%, rgba(34, 197, 94, 0.1) 0%, transparent 50%);
        animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
        0%, 100% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.1); }
    }

    /* Floating particles */
    .ilm-hero::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(2px 2px at 20% 30%, rgba(255, 255, 255, 0.3), transparent),
            radial-gradient(2px 2px at 60% 70%, rgba(250, 204, 21, 0.4), transparent),
            radial-gradient(1px 1px at 50% 50%, rgba(163, 230, 53, 0.3), transparent),
            radial-gradient(1px 1px at 80% 10%, rgba(255, 255, 255, 0.4), transparent),
            radial-gradient(2px 2px at 90% 60%, rgba(250, 204, 21, 0.3), transparent);
        background-size: 200% 200%;
        animation: particleFloat 20s ease infinite;
    }

    @keyframes particleFloat {
        0%, 100% { background-position: 0% 0%; }
        50% { background-position: 100% 100%; }
    }

    /* Hero text with glow effect */
    .ilm-hero h1 {
        color: #ffffff !important;
        text-shadow: 0 0 40px rgba(250, 204, 21, 0.5), 0 2px 8px rgba(0, 0, 0, 0.3);
        font-size: 3rem !important;
        font-weight: 900 !important;
        letter-spacing: -0.02em;
        line-height: 1.2;
        position: relative;
        z-index: 10;
    }

    .ilm-hero p.fs-5 {
        color: rgba(255, 255, 255, 0.9) !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        font-size: 1.25rem !important;
        line-height: 1.6;
        position: relative;
        z-index: 10;
    }

    /* Fix for buttons and content to be clickable */
    .ilm-hero .p-4,
    .ilm-hero .p-md-5 {
        position: relative;
        z-index: 10;
    }

    .ilm-hero .btn {
        position: relative;
        z-index: 10;
    }

    /* Badge with glassmorphism */
    .ilm-hero .badge {
        background: rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fef9c3 !important;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.85rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        animation: badgePulse 3s ease infinite;
        position: relative;
        z-index: 10;
    }

    @keyframes badgePulse {
        0%, 100% { transform: scale(1); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2); }
        50% { transform: scale(1.05); box-shadow: 0 12px 48px rgba(250, 204, 21, 0.4); }
    }

    /* Hero cards with glassmorphism */
    .ilm-hero .bg-white {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 10;
    }

    .ilm-hero .bg-white:hover {
        background: rgba(255, 255, 255, 0.25) !important;
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
        border-color: rgba(250, 204, 21, 0.5);
    }

    .ilm-hero .bg-white h6,
    .ilm-hero .bg-white p {
        color: #ffffff !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .ilm-hero .badge.rounded-pill.text-bg-success {
        background: linear-gradient(135deg, #22c55e, #16a34a) !important;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
    }

    .ilm-hero .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    /* Right side gradient background */
    .ilm-hero .col-lg-5 > div {
        background: linear-gradient(135deg, rgba(250, 204, 21, 0.2) 0%, rgba(163, 230, 53, 0.15) 40%, rgba(34, 197, 94, 0.2) 100%) !important;
    }

    /* ===== CATEGORY PILLS - COLORFUL REDESIGN ===== */
    .ilm-category-strip {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(247, 250, 247, 0.95));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(213, 227, 214, 0.6);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .ilm-category-pill {
        border: none;
        background: transparent;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1rem;
        cursor: pointer;
        border-radius: 999px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    /* Colorful gradient backgrounds for category icons */
    .ilm-category-pill:nth-child(1) .ilm-category-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .ilm-category-pill:nth-child(2) .ilm-category-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        box-shadow: 0 8px 20px rgba(245, 87, 108, 0.4);
    }

    .ilm-category-pill:nth-child(3) .ilm-category-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
    }

    .ilm-category-pill:nth-child(4) .ilm-category-icon {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        box-shadow: 0 8px 20px rgba(67, 233, 123, 0.4);
    }

    .ilm-category-pill:nth-child(5) .ilm-category-icon {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        box-shadow: 0 8px 20px rgba(250, 112, 154, 0.4);
    }

    .ilm-category-pill .ilm-category-icon {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #4ade80 0%, #16a34a 100%);
        box-shadow: 0 8px 20px rgba(74, 222, 128, 0.35);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    /* Shine effect on hover */
    .ilm-category-pill .ilm-category-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transform: rotate(45deg);
        transition: all 0.6s ease;
        opacity: 0;
    }

    .ilm-category-pill:hover .ilm-category-icon::before {
        left: 100%;
        opacity: 1;
    }

    .ilm-category-pill .ilm-category-label {
        font-size: 0.85rem;
        color: var(--ilm-text-muted);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .ilm-category-pill.active .ilm-category-icon {
        transform: scale(1.15);
        box-shadow: 0 12px 32px rgba(102, 126, 234, 0.6);
    }

    .ilm-category-pill.active .ilm-category-label {
        color: var(--ilm-primary-deep);
        font-weight: 700;
    }

    .ilm-category-pill:hover {
        transform: translateY(-5px);
        background: linear-gradient(135deg, rgba(245, 248, 246, 0.8), rgba(255, 255, 255, 0.9));
    }

    .ilm-category-pill:hover .ilm-category-icon {
        transform: scale(1.12) rotate(5deg);
    }

    /* ===== EVENT CARDS - MODERN REDESIGN ===== */
    .ilm-event-card {
        background: linear-gradient(135deg, #ffffff 0%, #fafbfa 100%);
        border-radius: 28px;
        padding: 1.5rem;
        box-shadow: 0 10px 40px rgba(15, 23, 15, 0.1);
        border: 1px solid rgba(213, 227, 214, 0.5);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    /* Gradient overlay that appears on hover */
    .ilm-event-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #43e97b 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .ilm-event-card:hover::before {
        opacity: 1;
    }

    /* Animated corner accent */
    .ilm-event-card::after {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(61, 123, 74, 0.08) 0%, transparent 70%);
        transition: all 0.6s ease;
    }

    .ilm-event-card:hover::after {
        top: -80px;
        right: -80px;
        background: radial-gradient(circle, rgba(61, 123, 74, 0.15) 0%, transparent 70%);
    }

    .ilm-event-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 20px 60px rgba(15, 23, 15, 0.18);
        border-color: rgba(61, 123, 74, 0.4);
    }

    /* Avatar with gradient ring */
    .ilm-event-avatar {
        width: 42px;
        height: 42px;
        border-radius: 18px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 800;
        color: #ffffff;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    /* Animated ring around avatar */
    .ilm-event-avatar::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 20px;
        padding: 2px;
        background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .ilm-event-card:hover .ilm-event-avatar {
        transform: rotate(360deg) scale(1.15);
        box-shadow: 0 8px 28px rgba(102, 126, 234, 0.6);
    }

    .ilm-event-card:hover .ilm-event-avatar::before {
        opacity: 1;
    }

    /* Image with overlay and zoom */
    .ilm-event-media {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.4s ease;
        aspect-ratio: 4 / 3;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Gradient overlay on image */
    .ilm-event-media::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.4) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .ilm-event-card:hover .ilm-event-media::after {
        opacity: 1;
    }

    .ilm-event-card:hover .ilm-event-media {
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        transform: scale(1.03);
    }

    .ilm-event-media img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        display: block;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ilm-event-card:hover .ilm-event-media img {
        transform: scale(1.08);
    }

    /* Placeholder with animated gradient */
    .ilm-event-media--placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 2.5rem 1rem;
        min-height: 200px;
        width: 100%;
        aspect-ratio: 4 / 3;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 50%, #f3f4f6 100%);
        background-size: 200% 200%;
        animation: gradientMove 3s ease infinite;
    }

    @keyframes gradientMove {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .ilm-event-media--placeholder .placeholder-icon {
        width: 48px;
        height: 48px;
        border-radius: 18px;
        background: linear-gradient(135deg, #cbd5e1, #e2e8f0);
        animation: shimmer 2s infinite;
    }

    .ilm-event-media--placeholder .placeholder-icon.large {
        width: 64px;
        height: 64px;
        border-radius: 22px;
    }

    .ilm-event-media--placeholder .placeholder-icon.small {
        width: 36px;
        height: 36px;
        border-radius: 14px;
    }

    @keyframes shimmer {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(0.98); }
    }

    /* Text styling */
    .ilm-event-card h5 {
        color: var(--ilm-primary-deep);
        font-weight: 700;
        font-size: 1.15rem;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        transition: color 0.3s ease;
    }

    .ilm-event-card:hover h5 {
        color: #0f2a1b;
    }

    .ilm-description-line {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.6;
        color: var(--ilm-text-muted);
        font-size: 0.9rem;
    }

    /* Enhanced badges */
    .badge {
        padding: 0.45rem 0.85rem;
        border-radius: var(--ilm-radius-pill);
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .text-bg-success {
        background: linear-gradient(135deg, #22c55e, #16a34a) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    /* Staggered animation for cards */
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

    .col-md-4, .col-sm-6 {
        animation: fadeInUp 0.6s ease both;
    }

    .col-md-4:nth-child(1), .col-sm-6:nth-child(1) { animation-delay: 0.1s; }
    .col-md-4:nth-child(2), .col-sm-6:nth-child(2) { animation-delay: 0.2s; }
    .col-md-4:nth-child(3), .col-sm-6:nth-child(3) { animation-delay: 0.3s; }
    .col-md-4:nth-child(4), .col-sm-6:nth-child(4) { animation-delay: 0.4s; }
    .col-md-4:nth-child(5), .col-sm-6:nth-child(5) { animation-delay: 0.5s; }
    .col-md-4:nth-child(6), .col-sm-6:nth-child(6) { animation-delay: 0.6s; }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .ilm-hero h1 {
            font-size: 2.2rem !important;
        }

        .ilm-category-pill .ilm-category-icon {
            width: 58px;
            height: 58px;
        }

        .ilm-event-card {
            border-radius: 24px;
            padding: 1.3rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('[data-category-filter]');
        const cards = document.querySelectorAll('[data-category-id]');

        if (!filterButtons.length || !cards.length) return;

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-category-filter');

                filterButtons.forEach(b => b.classList.remove('active'));
                button.classList.add('active');

                cards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category-id');
                    const shouldShow = target === 'all' || cardCategory === target;
                    card.classList.toggle('d-none', !shouldShow);
                });
            });
        });
    });
</script>

@endsection