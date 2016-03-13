<?php namespace BeEasy\Slider\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'bedard_shop_general_settings';

    public $settingsFields = 'fields.yaml';

    public $rules = [
        'pauseOnHover'      => 'boolean',
        'animationSpeed'    => 'required|integer|min:0',
        'autoplaySpeed'     => 'integer|min:0',
        'touchThreshold'    => 'integer|min:0',
    ];
}
