<?php namespace BeEasy\Slider\Http;

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
        return 'What\'s up from the SlidesController';
    }
}
