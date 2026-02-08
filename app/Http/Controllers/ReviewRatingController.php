<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin\Rating;

use App\Models\Admin\Review;
use App\Models\RatingReview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class ReviewRatingController extends Controller
{
    public function storeReview(Request $request)
    {

        if(auth()->user()){
            $request->validate([
                'product_id' => 'required|numeric',
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'required|string|max:500',
            ]);
        }else{
            $request->validate([
                'product_id' => 'required|numeric',
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'required|string|max:500',
                'author' => 'required',
                'email' => 'required|email',
            ]);
        }


        if(auth()->user()){
            $user = auth()->user();
        }else{
            $user = User::where('email', $request->email)->first();
            if(!$user){
                $user = new User;
                $user->name = $request->author;
                $user->email = $request->email;
                $user->is_guest = '1';
                $user->save();

                $role = Role::where('name', 'Customer')->first();
                $user->assignRole($role);
            }
        }

        $ratingReview = new RatingReview;
        $ratingReview->user_id = $user->id;
        $ratingReview->product_id = $request->product_id;
        $ratingReview->rating = $request->rating;
        $ratingReview->review = $request->review;
        $ratingReview->save();

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function storeRating(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $productId],
            ['rating' => $request->rating]
        );

        return redirect()->back()->with('success', 'Rating submitted successfully!');
    }
}
