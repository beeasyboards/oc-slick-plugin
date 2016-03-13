<?php namespace BeEasy\Slider\Components;

use BeEasy\Slider\Models\Settings;
use BeEasy\Slider\Models\Slide;
use Cms\Classes\ComponentBase;

class Slider extends ComponentBase
{
    /**
     * @var Collection      Active slides
     */
    public $slides;

    /**
     * @var array           Slider settings
     */
    public $settings;

    public function componentDetails()
    {
        return [
            'name'        => 'Slider',
            'description' => 'A touch-friendly responsive media slider.'
        ];
    }

    public function defineProperties()
    {
        return [
            'prevArrow' => [
                 'title'             => 'Previous arrow',
                 'description'       => 'Markup for the previous slide button.',
                 'type'              => 'string',
                 'showExternalParam' => false,
            ],
            'nextArrow' => [
                 'title'             => 'Next arrow',
                 'description'       => 'Markup for the next slide button.',
                 'type'              => 'string',
                 'showExternalParam' => false,
            ],
        ];
    }

    public function onRun()
    {
        // Query our active slides
        $this->slides = Slide::isActive()->inOrder()->with('image')->get();

        // Inject slider assets
        $this->addCss('/plugins/beeasy/slider/assets/css/slick.css');
        $this->addJs('/plugins/beeasy/slider/assets/js/slick.min.js');

        // Load slider settings
        $this->settings = [
            'adaptiveHeight'    => (bool) Settings::get('adaptiveHeight', true),
            'animationSpeed'    => intval(Settings::get('animationSpeed', 300)),
            'arrows'            => (bool) Settings::get('arrows', true),
            'autoplay'          => (bool) Settings::get('autoplay', true),
            'autoplaySpeed'     => intval(Settings::get('autoplaySpeed', 3000)),
            'cssEase'           => Settings::get('cssEase', 'ease'),
            'dots'              => (bool) Settings::get('dots', false),
            'nextArrow'         => $this->property('nextArrow') ?: '<button type="button" class="slick-next">Next</button>',
            'pauseOnHover'      => (bool) Settings::get('pauseOnHover', true),
            'prevArrow'         => $this->property('prevArrow') ?: '<button type="button" class="slick-prev">Prev</button>',
            'touchThreshold'    => intval(Settings::get('touchThreshold', 5)),
        ];

        // Disable autoplay if a video is in the slider
        foreach ($this->slides as $slide)
            if ($slide->type != 'image') $this->settings['autoplay'] = false;
    }

}
