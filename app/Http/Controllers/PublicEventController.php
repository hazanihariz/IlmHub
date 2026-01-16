<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index()
    {
        // Get all events with their category data, ordered by newest first
        $events = Event::with('category')->latest()->get();
        
        return view('welcome', compact('events'));
    }

    public function show($id)
    {
        // Find the event or show 404 error if not found
        $event = Event::with('category')->findOrFail($id);

        return view('events.show', compact('event'));
    }
}
