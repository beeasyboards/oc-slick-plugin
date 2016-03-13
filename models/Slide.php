<?php namespace BeEasy\Slider\Models;

use Model;

/**
 * Slide Model
 */
class Slide extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var boolean     Determines if the slides should be re-indexed after save or delete.
     */
    public $reindex = true;

    /**
     * @var string      The database table used by the model.
     */
    public $table = 'beeasy_slider_slides';

    /**
     * @var array       Fillable fields
     */
    protected $fillable = [
        'position',
        'href',
        'type',
        'name',
        'youtube',
        'is_new_window',
        'is_active',
    ];

    /**
     * @var array       Relations
     */
    public $attachOne = [
        'image' => ['System\Models\File'],
    ];

    /**
     * Validation
     */
    public $rules = [
        'name'          => 'required',
        'image'         => 'required_if:type,image',
        'youtube'       => 'required_if:type,youtube|max:11|alpha_dash',
        'is_new_window' => 'boolean',
        'is_active'     => 'boolean',
    ];

    public $customMessages = [
        'image.required_if'     => 'An image is required.',
        'youtube.required_if'   => 'A YouTube video ID is required.',
        'youtube.max'           => 'YouTube video IDs may not be longer than 11 characters.',
        'youtube.alpha_dash'    => 'YouTube video IDs may only contain letters, numbers, and dashes.',
    ];

    /**
     * Model events
     */
    public function beforeSave()
    {
        // Force an integer position for active slides, and null for inactive ones
        $this->position = $this->is_active
            ? intval($this->position)
            : null;

        // Prevent slides from being both images and videos
        if ($this->type == 'image') {
            $this->youtube = null;
        } elseif ($this->image) {
            $this->image->delete();
        }

    }

    public function afterSave()
    {
        // Reindex the slides
        if ($this->reindex) Slide::reindex();
    }

    public function afterDelete()
    {
        // Delete related image, and re-index the slides
        if ($this->image) $this->image->delete();
        if ($this->reindex) Slide::reindex();
    }

    /**
     * Query scopes
     */
    public function scopeInOrder($query)
    {
        // Sorts the slides by their position
        return $query->orderBy('position', 'asc');
    }

    public function scopeIsActive($query)
    {
        // Selects active slides
        return $query->where('is_active', true);
    }

    public function scopeIsNotActive($query)
    {
        // Selects disabled slides
        return $query->where('is_active', false);
    }

    /**
     * Hide the form inputs that aren't needed
     */
    public function filterFields($fields, $context = null)
    {
        $fields->youtube->hidden        = !$this->type || $this->type == 'image';
        $fields->image->hidden          = $this->type == 'youtube';
        $fields->href->hidden           = $this->type == 'youtube';
        $fields->is_new_window->hidden  = $this->type == 'youtube';
    }

    /**
     * Accessors
     */
    public function getIsImageAttribute()
    {
        return $this->type == 'image';
    }

    public function getIsYoutubeAttribute()
    {
        return $this->type == 'youtube';
    }

    /**
     * Re-indexes the slide positions
     */
    public static function reindex()
    {
        $slides = Slide::isActive()->inOrder()->get();

        $position = 1;
        foreach ($slides as $slide) {
            // Index the slide position
            $slide->position = $position;

            // Unset validation rules, and avoid a reindex loop
            $slide->rules = [];
            $slide->reindex = false;

            // Save the slide
            $position++;
            $slide->save();
        }
    }

}
