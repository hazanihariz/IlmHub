<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Handle the Booking Logic
    public function store(Request $request, $eventId)
    {
        // 1. Check if User is Logged In
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to book a ticket.');
        }

        $user = Auth::user();
        $event = Event::findOrFail($eventId);

        // 2. DUPLICATE CHECK: Has this user already booked?
        // We look for a booking where user_id matches AND event_id matches
        $existingBooking = Booking::where('user_id', $user->id)
                                  ->where('event_id', $event->id)
                                  ->where('status', '!=', 'cancelled') // Ignore cancelled bookings
                                  ->first();

        if ($existingBooking) {
            return back()->with('error', 'You have already booked a seat for this event.');
        }

        // 3. CAPACITY CHECK: Is the event full?
        // Count how many confirmed bookings exist for this event
        $currentBookings = Booking::where('event_id', $event->id)
                                  ->where('status', 'confirmed')
                                  ->count();

        if ($currentBookings >= $event->capacity) {
            return back()->with('error', 'Sorry, this event is fully booked.');
        }

        // 4. CREATE THE BOOKING
        Booking::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'booking_time' => now(),
        ]);

        // 5. Success!
        return redirect()->route('home')->with('success', 'Booking confirmed! May you benefit from this knowledge.');
    }


    // 1. List all bookings for the current user
    public function index()
    {
        // Get bookings with Event details, ordered by newest first
        $bookings = Auth::user()->bookings()->with('event')->latest()->get();
        
        return view('bookings.index', compact('bookings'));
    }

    // 2. Cancel a booking
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Security Check: Ensure the logged-in user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->delete();

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
