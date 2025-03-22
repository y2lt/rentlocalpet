<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pet;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['permission:create bookings'])->only(['store', 'create']);
        $this->middleware(['permission:approve bookings'])->only(['approve', 'reject']);
        $this->middleware(['check.booking'])->except(['index', 'store', 'create']);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $user = Auth::user();
        $query = Booking::query()->with(['pet.user', 'user']);

        if ($user->hasRole('pet owner')) {
            $query->whereHas('pet', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } else if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        return BookingResource::collection($query->latest()->paginate(10));
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $pet = Pet::findOrFail($validated['pet_id']);

        if (!$pet->is_available) {
            return response()->json(['message' => 'This pet is not available for booking'], 422);
        }

        // Calculate total price
        $days = now()->parse($validated['start_date'])->diffInDays($validated['end_date']);
        $totalPrice = $days * $pet->daily_rate;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'pet_id' => $pet->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'special_requests' => $validated['special_requests'] ?? null,
        ]);

        return response()->json(new BookingResource($booking->load('pet')), 201);
    }

    public function show(Booking $booking): JsonResponse
    {
        return response()->json(new BookingResource($booking->load(['pet.user', 'user'])));
    }

    public function approve(Booking $booking): JsonResponse
    {
        $this->authorize('update', $booking);

        if ($booking->status !== 'pending') {
            return response()->json(['message' => 'This booking cannot be approved'], 422);
        }

        $booking->update(['status' => 'approved']);

        return response()->json(new BookingResource($booking));
    }

    public function reject(Booking $booking): JsonResponse
    {
        $this->authorize('update', $booking);

        if ($booking->status !== 'pending') {
            return response()->json(['message' => 'This booking cannot be rejected'], 422);
        }

        $booking->update(['status' => 'rejected']);

        return response()->json(new BookingResource($booking));
    }

    public function cancel(Booking $booking): JsonResponse
    {
        $this->authorize('update', $booking);

        if (!in_array($booking->status, ['pending', 'approved'])) {
            return response()->json(['message' => 'This booking cannot be cancelled'], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json(new BookingResource($booking));
    }
}
