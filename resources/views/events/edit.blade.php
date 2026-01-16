@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-success">Edit Event</h4>
                <a href="{{ route('events.mine') }}" class="btn btn-sm btn-outline-secondary">Back to My Events</a>
            </div>
            <div class="card-body">
                
                <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Event Title</label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title', $event->title) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $event->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Capacity (Seats)</label>
                            <input type="number" name="capacity" class="form-control" required 
                                   value="{{ old('capacity', $event->capacity) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" name="event_date" class="form-control" required
                                   value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" required 
                                   value="{{ old('location', $event->location) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender Policy</label>
                        <select name="gender_policy" class="form-select" required>
                            <option value="mixed" @selected(old('gender_policy', $event->gender_policy) == 'mixed')>Mixed</option>
                            <option value="segregated" @selected(old('gender_policy', $event->gender_policy) == 'segregated')>
                                Segregated (Mixed with barrier)
                            </option>
                            <option value="males_only" @selected(old('gender_policy', $event->gender_policy) == 'males_only')>Males Only</option>
                            <option value="females_only" @selected(old('gender_policy', $event->gender_policy) == 'females_only')>Females Only</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Event Poster</label>
                        
                        @if($event->image_path)
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Current Poster:</small>
                                <img src="{{ asset('storage/' . str_replace('\\', '/', $event->image_path)) }}" 
                                     alt="{{ $event->title }} poster" 
                                     class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        @endif

                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-1">
                            Upload a new image to replace the current one. JPG, PNG, GIF, or WebP up to 2MB.
                        </small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: #f9f9f9;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    .card {
        border-radius: 8px;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        border-bottom: 1px solid #e0e0e0;
    }

    .btn-success {
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-success:hover {
        background-color: #388e3c;
        transform: scale(1.05);
    }

    .form-control:focus {
        border-color: #43a047;
        box-shadow: 0 0 5px rgba(67, 160, 71, 0.5);
    }

    .img-preview {
        max-height: 200px;
        border-radius: 6px;
        margin-top: 10px;
    }

    .char-counter {
        font-size: 0.85rem;
        color: #666;
        text-align: right;
    }
</style>

@endsection


