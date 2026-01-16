<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * List events created by the currently authenticated user.
     */
    public function index()
    {
        $events = Auth::user()
            ->events()
            ->with('category')
            ->latest()
            ->get();

        return view('events.my', compact('events'));
    }

    // 1. Show the form to create a new event
    public function create()
    {
        // We need the categories to show in the dropdown menu
        // De-duplicate by name to avoid repeated options in the dropdown
        $categories = Category::query()
            ->orderBy('name')
            ->get()
            ->unique('name')
            ->values();
        return view('events.create', compact('categories'));
    }

    // 2. Store the new event in the database
    public function store(Request $request)
    {
        // Validation (Security)
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'event_date' => 'required|date',
            'location' => 'required',
            'capacity' => 'required|integer',
            'gender_policy' => 'required', // Enum validation happens here
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Prepare data for saving (excluding the raw file)
        $data = $request->except('image');

        // Attach the creator (current user)
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        // Handle optional image upload
        if ($request->hasFile('image')) {
            // Store directly inside the public/events folder so the browser can always see it
            $image = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());

            $destinationPath = public_path('events');
            if (! is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $filename);

            // Save relative path like "events/filename.jpg"
            $data['image_path'] = 'events/' . $filename;
        }

        // Save Data
        Event::create($data);

        // Redirect back with a success message
        return redirect()->route('events.create')->with('success', 'Event created successfully!');
    }

    /**
     * Show the edit form for a specific event (owned by the user).
     */
    public function edit(Event $event)
    {
        // Security: Only the creator can edit
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // De-duplicate by name to avoid repeated options in the dropdown
        $categories = Category::query()
            ->orderBy('name')
            ->get()
            ->unique('name')
            ->values();

        return view('events.edit', compact('event', 'categories'));
    }

    /**
     * Update a specific event.
     */
    public function update(Request $request, Event $event)
    {
        // Security: Only the creator can update
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'event_date' => 'required|date',
            'location' => 'required',
            'capacity' => 'required|integer',
            'gender_policy' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('image');

        // Handle optional image replacement
        if ($request->hasFile('image')) {
            // Delete old image file from public/events if it exists
            if ($event->image_path) {
                $oldPath = public_path($event->image_path);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $image = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());

            $destinationPath = public_path('events');
            if (! is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $filename);

            $data['image_path'] = 'events/' . $filename;
        }

        $event->update($data);

        return redirect()->route('events.mine')->with('success', 'Event updated successfully!');
    }

    /**
     * Delete a specific event.
     */
    public function destroy(Event $event)
    {
        // Security: Only the creator can delete
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image file from public/events if exists
        if ($event->image_path) {
            $oldPath = public_path($event->image_path);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $event->delete();

        return redirect()->route('events.mine')->with('success', 'Event deleted successfully.');
    }
}