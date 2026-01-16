@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold text-success">Create New Event</h4>
            </div>
            <div class="card-body">
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 
                    
                    <div class="mb-3">
                        <label class="form-label">Event Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" required placeholder="e.g. Fiqh of Fasting">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Capacity (Seats)</label>
                            <input type="number" name="capacity" value="{{ old('capacity') }}" class="form-control" required placeholder="e.g. 50">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" value="{{ old('location') }}" class="form-control" required placeholder="e.g. Main Musolla">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender Policy</label>
                        <select name="gender_policy" class="form-select" required>
                            <option value="mixed" {{ old('gender_policy') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                            <option value="segregated" {{ old('gender_policy') == 'segregated' ? 'selected' : '' }}>Segregated (Mixed with barrier)</option>
                            <option value="males_only" {{ old('gender_policy') == 'males_only' ? 'selected' : '' }}>Males Only</option>
                            <option value="females_only" {{ old('gender_policy') == 'females_only' ? 'selected' : '' }}>Females Only</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Event Poster (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-1">Upload a JPG, PNG, GIF, or WebP file up to 2MB.</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Publish Event</button>
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

    .alert {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@endsection
