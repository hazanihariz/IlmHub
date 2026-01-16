@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h3 class="mb-4 fw-bold border-bottom pb-2">My Events</h3>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Event</th>
                                <th>Date & Time</th>
                                <th>Category</th>
                                <th class="text-center">Capacity</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr>
                                    <td class="ps-4">
                                        <strong>{{ $event->title }}</strong><br>
                                        <small class="text-muted">{{ $event->location }}</small>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, h:i A') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success">
                                            {{ $event->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $event->capacity }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-primary me-2">
                                            Edit
                                        </a>

                                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this event? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <h5 class="text-muted">You haven't created any events yet.</h5>
                                        <a href="{{ route('events.create') }}" class="btn btn-success mt-2">Create Your First Event</a>
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

<style>
    body {
        background: #f9f9f9;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    h3 {
        color: #2e7d32;
    }

    .card {
        border-radius: 8px;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .table-hover tbody tr:hover {
        background-color: #f1f8e9;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .badge {
        font-size: 0.85rem;
        padding: 6px 10px;
        border-radius: 6px;
    }

    .btn-outline-primary:hover {
        background-color: #1976d2;
        color: #fff;
        transform: scale(1.05);
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover {
        background-color: #d32f2f;
        color: #fff;
        transform: scale(1.05);
        transition: all 0.3s ease;
    }

    .empty-state h5 {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@endsection


