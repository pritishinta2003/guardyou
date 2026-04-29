<?php

namespace App\Http\Controllers;

use App\Models\Bodyguard;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $featuredBodyguards = Bodyguard::with('user')
            ->where('is_verified', true)
            ->orderBy('experience_years', 'desc')
            ->take(3)
            ->get();

        return view('landing', compact('featuredBodyguards'));
    }

    public function howItWorks()
    {
        return view('how-it-works');
    }
}
