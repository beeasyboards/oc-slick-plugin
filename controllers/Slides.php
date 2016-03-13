<?php namespace BeEasy\Slider\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use BeEasy\Slider\Models\Slide;
use Flash;

/**
 * Slides Back-end Controller
 */
class Slides extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('BeEasy.Slider', 'slick', 'slides');
    }

    public function index($userId = null)
    {
        $this->addCss('/plugins/beeasy/slider/assets/css/list.css');
        $this->addJs('/plugins/beeasy/slider/assets/js/jquery.sortable.js');
        $this->addJs('/plugins/beeasy/slider/assets/js/list.js');
        $this->prepareVars();
    }

    public function listExtendQuery($query)
    {
        $query->inOrder();
    }

    private function prepareVars()
    {
        $this->asExtension('ListController')->index();
        $this->vars['slidesActive']     = Slide::isActive()->count();
        $this->vars['slidesDisabled']   = Slide::isNotActive()->count();
        $this->vars['slidesTotal']      = $this->vars['slidesActive'] + $this->vars['slidesDisabled'];
    }

    /**
     * Returns a listRefresh and updates the scoreboard
     *
     * @return  array
     */
    private function refreshListAndScoreboard()
    {
        $this->prepareVars();
        $response = $this->listRefresh();
        $response['#scoreboard'] = $this->makePartial('list_scoreboard');
        return $response;
    }

    /**
     * Loads the reorder popup
     */
    public function index_onLoadPopup()
    {
        return $this->makePartial('popup', [
            'slides' => Slide::isActive()->inOrder()->get(),
        ]);
    }

    /**
     * Reorders active slides
     *
     * @return  array
     */
    public function index_onReorderSlides()
    {
        $positions = input('bedard_slick_slide');
        if ($positions && is_array($positions) && count($positions)) {
            $slides = Slide::isActive()->get();
            foreach ($positions as $position => $id) {
                if (!$slide = $slides->find($id)) continue;
                $slide->position = $position + 1;
                $slide->reindex = false;
                $slide->save();
            }
            Flash::success('Slides successfully reordered.');
        }

        return $this->listRefresh();
    }

    /**
     * Activates a collection of slides
     *
     * @return  array
     */
    public function index_onActivate()
    {
        $checkedIds = input('checked');
        if ($checkedIds && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $id) {
                if (!$model = Slide::find($id)) continue;
                $model->is_active = true;
                $model->reindex = false;
                $model->save();
            }
            Slide::reindex();
            Flash::success("Slides successfully activated.");
        }

        return $this->refreshListAndScoreboard();
    }

    /**
     * Disables a collection of slides
     *
     * @return  array
     */
    public function index_onDisable()
    {
        $checkedIds = input('checked');
        if ($checkedIds && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $id) {
                if (!$model = Slide::find($id)) continue;
                $model->is_active = false;
                $model->reindex = false;
                $model->save();
            }
            Slide::reindex();
            Flash::success("Slides successfully disabled.");
        }

        return $this->refreshListAndScoreboard();
    }

    /**
     * Deletes a collection of slides
     *
     * @return  array
     */
    public function index_onDelete()
    {
        $checkedIds = input('checked');
        if ($checkedIds && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $id) {
                if (!$model = Slide::find($id)) continue;
                $model->reindex = false;
                $model->delete();
            }
            Slide::reindex();
            Flash::success("Slides successfully deleted.");
        }

        return $this->refreshListAndScoreboard();
    }
}
