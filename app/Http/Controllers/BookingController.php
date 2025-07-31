<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['field', 'payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        // Authorize user can only see their own booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['field', 'payment']);

        return view('bookings.show', compact('booking'));
    }

    public function create(Field $field, Request $request)
    {
        $date = $request->get('date');
        $time = $request->get('time');

        return view('bookings.create', compact('field', 'date', 'time'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'duration_hours' => 'required|integer|min:1|max:8',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $field = Field::findOrFail($request->field_id);

        // Calculate end time and total price
        $startTime = $request->start_time;
        $duration = (int) $request->duration_hours;
        $endTime = date('H:i:s', strtotime($startTime . ' + ' . $duration . ' hours'));
        $totalPrice = $field->price_per_hour * $duration;

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'field_id' => $field->id,
            'booking_code' => Booking::generateBookingCode(),
            'booking_date' => $request->booking_date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $duration,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'notes' => $request->notes,
        ]);

        // Create payment record
        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $totalPrice,
            'payment_method' => 'transfer',
            'status' => 'pending',
        ]);

        return redirect()->route('payments.show', $booking->id)
            ->with('success', 'Booking berhasil dibuat! Silakan upload bukti pembayaran.');
    }

    public function cancel(Booking $booking)
    {
        // Authorize user can only cancel their own booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function payment(Booking $booking)
    {
        // Authorize user can only see their own booking payment
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['field', 'payment']);

        return view('payments.upload', compact('booking'));
    }

    public function uploadPayment(Request $request, Booking $booking)
    {
        // Authorize user can only upload payment for their own booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'proof_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bank_name' => 'required|string|max:100',
            'account_holder_name' => 'required|string|max:100',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('payment_proofs', $filename, 'public');

            // Update payment record
            $booking->payment->update([
                'proof_file_path' => $path,
                'proof_file_name' => $filename,
                'bank_name' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'payment_notes' => $request->payment_notes,
                'status' => 'pending_verification',
                'paid_at' => now(),
            ]);

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
        }

        return back()->with('error', 'Gagal mengupload bukti pembayaran.');
    }
}