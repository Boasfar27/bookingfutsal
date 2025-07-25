<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Field;
use Carbon\Carbon;

class FieldController extends Controller
{
    public function index(Request $request)
    {
        $query = Field::active()->with('admin');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type') && $request->get('type') !== 'all') {
            $query->where('type', $request->get('type'));
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price_per_hour', '>=', $request->get('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_hour', '<=', $request->get('max_price'));
        }

        // Sort options
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price_per_hour', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price_per_hour', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $fields = $query->paginate(12);

        // Get field types for filter dropdown
        $fieldTypes = Field::select('type')
            ->distinct()
            ->pluck('type')
            ->toArray();

        return view('fields.index', compact('fields', 'fieldTypes'));
    }

    public function show(Field $field)
    {
        // Load relationships
        $field->load('admin', 'schedules');

        // Get available time slots for today
        $today = Carbon::today();
        $availableSlots = $field->getAvailableSlots($today->format('Y-m-d'));

        // Get recent reviews/bookings (if you plan to add review system later)
        $recentBookings = $field->bookings()
            ->with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        return view('fields.show', compact('field', 'availableSlots', 'recentBookings'));
    }

    public function getAvailableSlots(Request $request, Field $field)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        // Validate date format
        try {
            $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        // Don't allow booking for past dates
        if ($carbonDate->isPast()) {
            return response()->json(['slots' => []]);
        }

        $slots = $field->getAvailableSlots($date);

        return response()->json(['slots' => $slots]);
    }
}