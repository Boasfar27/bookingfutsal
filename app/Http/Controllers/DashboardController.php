<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user statistics
        $totalBookings = Booking::where('user_id', $user->id)->count();
        $activeBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('booking_date', '>=', now()->toDateString())
            ->count();
        
        $completedBookings = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
            
        $totalSpent = Payment::whereHas('booking', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'verified')->sum('amount');

        // Get recent bookings
        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['field', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get upcoming bookings
        $upcomingBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('booking_date', '>=', now()->toDateString())
            ->with(['field', 'payment'])
            ->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(3)
            ->get();

        // Get favorite fields (most booked)
        $favoriteFields = Field::whereHas('bookings', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->withCount(['bookings' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->orderBy('bookings_count', 'desc')->limit(3)->get();

        return view('dashboard', compact(
            'totalBookings',
            'activeBookings', 
            'completedBookings',
            'totalSpent',
            'recentBookings',
            'upcomingBookings',
            'favoriteFields'
        ));
    }
}