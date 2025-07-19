<?php

namespace App\Http\Controllers\API\Rating;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Review;
use Illuminate\Http\Request;

class Reviews_Ratings_Doctors extends Controller
{
    // create function
    public function create(Request $request)
    {
        // Check if the user is logged in
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $user_id = $user->id;

        // Validate incoming request data
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|min:10|max:300',
        ]);

        // Get the patient record linked to this user
        $patient = Patient::where('user_id', $user_id)->first();
        if (!$patient) {
            return response()->json(['message' => 'Patient not found.'], 404);
        }

        $doctor_id = $request->input('doctor_id');

        // Check if the patient had at least one appointment with the doctor
        $hasAppointment = Appointment::where('doctor_id', $doctor_id)
            ->where('patient_id', $patient->id)
            ->exists();

        if (!$hasAppointment) {
            return response()->json(['message' => 'You are not allowed to review this doctor. Only patients who had an appointment can leave a review.'], 406);
        }

        // Check if the user already submitted a review for this doctor
        $hasReviewed = Review::where('user_id', $user_id)
            ->where('doctor_id', $doctor_id)
            ->exists();

        if ($hasReviewed) {
            return response()->json(['message' => 'You already reviewed this doctor.'], 406);
        }

        Review::create([
            'user_id' => $user_id,
            'doctor_id' => $doctor_id,
            'rating' => $request->input('rating'),
            'comment' => strip_tags($request->input('comment')),
        ]);
        return response()->json([
            "message" => "Review submitted successfully.",
        ], 201);
    }

    // update function
    public function update(Request $request, $review_id)
    {
        // Check if the user is logged in
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
        $user_id = $user->id;
        // Check if the review exists and return it
        $review = Review::where('id', $review_id)->where('user_id', $user_id)->first();
        if (!$review) {
            return response()->json(['message' => 'Review not found or access denied.'], 404);
        }
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|min:10|max:300',
        ]);
        $review->update([
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment')
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Review updated successfully.',
            'data' => $review
        ], 200);
    }

    public function delete($review_id)
    {
        // Check if the user is logged in
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
        $user_id = $user->id;
        // Check if the review exists and return it
        $review = Review::where('id', $review_id)->where('user_id', $user_id)->first();
        if (!$review) {
            return response()->json(['message' => 'Review not found or access denied.'], 404);
        }
        $review->delete();
        return response()->json([
            'status' => true,
            'message' => 'Review deleted successfully.'
        ], 200);
    }

    public function show($review_id)
    {
        $user = auth()->user();
        $review = Review::with('user:id,name')
            ->where('id', $review_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$review) {
            return response()->json(['message' => 'Review not found.'], 404);
        }
        return response()->json(
            ['review' => $review]
            , 200);
    }

    public function doctorReviews($doctor_id)
    {
        $reviews = Review::where('doctor_id', $doctor_id)
            ->with('user:id,name')
            ->latest()
            ->get();

        return response()->json(['reviews' => $reviews], 200);
    }

    public function myReviews()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $reviews = Review::where('user_id', $user->id)
            ->with('doctor:id,name')
            ->latest()
            ->get();

        return response()->json(['reviews' => $reviews], 200);
    }


}
