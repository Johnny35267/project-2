<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
public function addToFavorite(Request $request, $apartmentId)
{
    $user = $request->user();

    if (! $user) {
        return response()->json([
            'status' => 0,
            'message' => 'Unauthenticated'
        ], 401);
    }

    Apartment::findOrFail($apartmentId);

    $exists = Favorite::where('user_id', $user->id)
        ->where('apartment_id', $apartmentId)
        ->where('favorite', true)
        ->exists();

    if ($exists) {
        return response()->json([
            'status' => 0,
            'message' => 'Apartment already in favorites'
        ], 409);
    }

    Favorite::Create(
        [
            'user_id' => $user->id,
            'apartment_id' => $apartmentId, 
            'favorite' => true
        ]
    );

    return response()->json([
        'status' => 1,
        'message' => 'Apartment added to favorites'
    ], 200);
}

public function removeFromFavorite(Request $request, $apartmentId)
{
    $user = $request->user();

    if (! $user) {
        return response()->json([
            'status' => 0,
            'message' => 'Unauthenticated'
        ], 401);
    }

    $favorite = Favorite::where('user_id', $user->id)
        ->where('apartment_id', $apartmentId)
        ->where('favorite', true)
        ->first();

    if (! $favorite) {
        return response()->json([
            'status' => 0,
            'message' => 'Apartment not in favorites'
        ], 404);
    }

    $favorite->update([
        'favorite' => false
    ]);

    return response()->json([
        'status' => 1,
        'message' => 'Apartment removed from favorites'
    ], 200);
}

public function myFavorites(Request $request)
{
    $user = $request->user();

    if (! $user) {
        return response()->json([
            'status' => 0,
            'message' => 'Unauthenticated'
        ], 401);
    }

    if ($user->role !== 'tenant') {
        return response()->json([
            'status' => 0,
            'message' => 'Only tenants can view favorites'
        ], 403);
    }

    $perPage = (int) $request->get('per_page', 10);
    $page    = (int) $request->get('page', 1);

    $paginator = Apartment::with('images')
        ->withAvg('ratings', 'rating')     
        ->withCount('ratings')             
        ->whereHas('favorites', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->where('favorite', true);
        })
        ->where('is_approved', true)
        ->paginate($perPage, ['*'], 'page', $page);

    return response()->json([
        'status' => 1,
        'message' => 'Favorite apartments',
        'data' => $paginator->items()
    ], 200);
}
}