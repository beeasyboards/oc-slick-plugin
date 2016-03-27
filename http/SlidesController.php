<?php namespace BeEasy\Slider\Http;

use BeEasy\Slider\Models\Slide;
use Illuminate\Routing\Controller;

class SlidesController extends Controller
{

    /**
     * Fetch the slides
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Slide::isActive()->inOrder()->with('image')->get();
    }
}
