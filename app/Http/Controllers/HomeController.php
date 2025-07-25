<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Field;
use App\Models\Booking;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Get some basic stats for the homepage
        $stats = [
            'total_fields' => Field::active()->count(),
            'total_bookings' => Booking::count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
            'featured_fields' => Field::active()
                ->with('admin')
                ->take(3)
                ->get()
        ];

        return view('home', compact('stats'));
    }
}