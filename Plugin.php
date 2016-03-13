<?php namespace BeEasy\Slider;

use Backend;
use System\Classes\PluginBase;

/**
 * Slick Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Slick',
            'description' => 'A touch-friendly image and video slider.',
            'author'      => 'Scott Bedard',
            'icon'        => 'icon-picture-o'
        ];
    }

    /**
     * Registers the backend navigation items
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'slick' => [
                'label'       => 'Slider',
                'url'         => Backend::url('beeasy/slider/slides'),
                'icon'        => 'icon-picture-o',
                'permissions' => ['BeEasy.Slider.*'],
                'order'       => 600,

                'sideMenu' => [
                    'slides' => [
                        'label'         => 'Slides',
                        'icon'          => 'icon-picture-o',
                        'url'           => Backend::url('beeasy/slider/slides'),
                        'permissions'   => ['BeEasy.Slider.access_slides'],
                    ],
                    'settings' => [
                        'label'         => 'Settings',
                        'icon'          => 'icon-cog',
                        'url'           => Backend::url('system/settings/update/beeasy/slider/general'),
                        'permissions'   => ['BeEasy.Slider.access_settings'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Register the backend settings
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'general' => [
                'label'         => 'Slick',
                'icon'          => 'icon-picture-o',
                'description'   => 'A touch-friendly image and video slider.',
                'class'         => 'BeEasy\Slider\Models\Settings',
                'order'         => 100,
                'keywords'      => 'slider'
            ],
        ];
    }

    /**
     * Registers the frontend components
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'BeEasy\Slider\Components\Slider' => 'slickSlider',
        ];
    }

}
