<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetType;
use App\Http\Requests\StorePetRequest;
use App\Http\Resources\PetResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Database\Eloquent\Builder;

class PetController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Pet::class, 'pet');
    }
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Pet::query()
            ->with(['type', 'breed', 'user'])
            ->withAvg('reviews', 'rating');

        if ($request->type_id) {
            $query->byType($request->type_id);
        }

        if ($request->breed_id) {
            $query->byBreed($request->breed_id);
        }

        if ($request->size) {
            $query->bySize($request->size);
        }

        if ($request->min_price && $request->max_price) {
            $query->byPriceRange($request->min_price, $request->max_price);
        }

        if ($request->available) {
            $query->available();
        }

        return PetResource::collection($query->paginate(12));
    }

    public function myPets(Request $request): AnonymousResourceCollection
    {
        $pets = Pet::where('user_id', Auth::id())
            ->with(['type', 'breed'])
            ->withAvg('reviews', 'rating')
            ->paginate(12);
        return PetResource::collection($pets);
    }

    public function store(StorePetRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (!auth()->user()->hasRole('pet owner')) {
            abort(403, 'Only pet owners can create pets');
        }

        $validated['user_id'] = auth()->id();
        $validated['is_available'] = true;

        $pet = Pet::create($validated);

        return response()->json(new PetResource($pet), 201);
    }

    public function show(Pet $pet): JsonResponse
    {
        $pet->load(['type', 'breed', 'user', 'reviews.user'])
            ->loadAvg('reviews', 'rating');
        return response()->json(new PetResource($pet));
    }

    public function toggleAvailability(Pet $pet): JsonResponse
    {
        $this->authorize('toggleAvailability', $pet);
        
        $pet->update(['is_available' => !$pet->is_available]);
        
        return response()->json(new PetResource($pet));
    }

    public function update(Request $request, Pet $pet): JsonResponse
    {
        $this->authorize('update', $pet);

        $validated = $request->validate([
            'pet_type_id' => 'sometimes|exists:pet_types,id',
            'pet_breed_id' => 'nullable|exists:pet_breeds,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'age' => 'sometimes|integer|min:0',
            'size' => 'sometimes|in:small,medium,large',
            'daily_rate' => 'sometimes|numeric|min:0',
            'is_available' => 'sometimes|boolean',
            'care_instructions' => 'nullable|array',
            'photos' => 'sometimes|array|min:1',
            'photos.*' => 'required|string|url'
        ]);

        $pet->update($validated);

        return response()->json(new PetResource($pet));
    }

    public function destroy(Pet $pet): JsonResponse
    {
        $this->authorize('delete', $pet);
        
        $pet->delete();
        
        return response()->json(null, 204);
    }

    public function getTypes()
    {
        return PetType::active()->get();
    }

    public function getBreeds(PetType $type)
    {
        return $type->breeds()->active()->get();
    }
}
