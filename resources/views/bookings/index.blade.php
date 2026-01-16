@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h3 class="mb-4 fw-bold border-bottom pb-2">My Bookings</h3>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Event</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="ps-4">
                                        <strong>{{ $booking->event->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $booking->event->location }}</small>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($booking->event->event_date)->format('d M Y, h:i A') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Confirmed</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to cancel this ticket?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <h5 class="text-muted">You haven't booked any events yet.</h5>
                                        <a href="{{ route('home') }}" class="btn btn-primary mt-2">Find an Event</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection