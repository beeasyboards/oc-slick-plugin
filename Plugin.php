<?php namespace Bedard\Slick;

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
                'url'         => Backend::url('bedard/slick/slides'),
                'icon'        => 'icon-picture-o',
                'permissions' => ['Bedard.Slick.*'],
                'order'       => 600,

                'sideMenu' => [
                    'slides' => [
                        'label'         => 'Slides',
                        'icon'          => 'icon-picture-o',
                        'url'           => Backend::url('bedard/slick/slides'),
                        'permissions'   => ['Bedard.Slick.access_slides'],
                    ],
                    'settings' => [
                        'label'         => 'Settings',
                        'icon'          => 'icon-cog',
                        'url'           => Backend::url('system/settings/update/bedard/slick/general'),
                        'permissions'   => ['Bedard.Slick.access_settings'],
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
                'class'         => 'Bedard\Slick\Models\Settings',
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
            'Bedard\Slick\Components\Slider' => 'slickSlider',
        ];
    }

}
