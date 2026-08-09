<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with(['order', 'customer'])->latest()->paginate(10);
        $avgRating = number_format(Review::avg('rating') ?? 5, 1);
        $totalReviews = Review::count();

        return view('admin.reviews.index', compact('reviews', 'avgRating', 'totalReviews'));
    }
}