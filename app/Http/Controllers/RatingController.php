<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Rating;

use App\Models\Booking;
use Illuminate\Http\Request;

class RatingController extends Controller
{
public function addRating(Request $request, $apartmentId)
{
    $user = $request->user();

    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
    ]);

    $hasBooking = Booking::where('user_id', $user->id)
        ->where('apartment_id', $apartmentId)
        ->where('status', 'approved')
        ->exists();

    if (! $hasBooking) {
        return response()->json([
            'status'=>0,
            'data'=>[],
            'message' => 'You can only rate an apartment you have booked.'
        ], 403);
    }
$hasRating = Rating::where('user_id', $user->id)
        ->where('apartment_id', $apartmentId)->exists();
        if($hasRating){
            return response()->json([
            'status'=>0,
            'data'=>[],
            'message' => 'you rated this befor'
        ], 403);
        }

    $rating = Rating::Create(
        [
            'user_id' => $user->id,
            'apartment_id' => $apartmentId,
            'rating' => $request->rating,
        ]
    );

    return response()->json([
        'status'=>1,
        'message' => 'Rating saved successfully.',
        'data' => $rating
    ]);
}

public  function updateRating(Request $request, $apartmentId)
{
    $user = $request->user();

    $request->validate([
        'rating' => 'sometimes|integer|min:1|max:5',
    ]);

    $rating = Rating::where('user_id', $user->id)
        ->where('apartment_id', $apartmentId)
        ->first();

    if (!$rating) {
        return response()->json([
            'message' => 'Rating not found or you are not authorized.'
        ], 404);
    }

    if ($request->has('rating')) {
        $rating->rating = $request->rating;
    }

  
    $rating->save();

    return response()->json([
        'message' => 'Rating updated successfully.',
        'data' => $rating
    ]);
}
public function deleteRating(Request $request, $apartmentId)
{
    $user = $request->user();

    $rating = Rating::where('user_id', $user->id)
        ->where('apartment_id', $apartmentId)
        ->first();

    if (!$rating) {
        return response()->json([
            'message' => 'Rating not found or you are not authorized.'
        ], 404);
    }

    $rating->delete();

    return response()->json([
        'message' => 'Rating deleted successfully.'
    ]);
}
}
