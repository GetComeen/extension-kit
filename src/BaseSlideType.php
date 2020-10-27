<?php


namespace DynamicScreen\ExtensionKit;

use App\Models\Account;
use App\Models\Slide;

abstract class BaseSlideType
{
    private $slide_buffer = [];
    /**
     * @var ExtensionContract
     */
    protected $extension = null;

    protected $hidden = false;

    abstract public function getName();
    abstract public function fetchSlide(SlideContract $slide);

    public function registerSlideInDisplay() {}
    public function registerSlideInApi() {}

    public function getIdentifier()
    {
        return str_slug($this->getName());
    }

    public final function getFullIdentifier()
    {
        return $this->extension->getIdentifier() . '.' . $this->getIdentifier();
    }

    public function getDescription()
    {
        return '';
    }

    public function getIcon()
    {
        return 'square';
    }

    /**
     * @return boolean
     */
    abstract public function hasDuration();

    public function getDefaultDuration()
    {
        return 5;
    }

    public function getColor()
    {
        return '#239d00';
    }

    public function areMediasExpired($medias)
    {
        return false;
    }

    public function isCompatibleWithDisplayMode()
    {
        return true;
    }

    public function isCompatibleWithInteractiveMode()
    {
        return true;
    }

    public function getDefaultOptions()
    {
        return [];
    }

    public function slideOptionsView()
    {
        return null;
    }

    public function processOptions($options)
    {
        return $options;
    }

    final protected function slide($data)
    {
        $this->slide_buffer[] = $data;
        return $this;
    }

    final public function flushSlides()
    {
        $slides = $this->slide_buffer;
        $this->slide_buffer = [];
        return $slides;
    }

    final public function toArray()
    {
        return [
            'full_identifier' => $this->getFullIdentifier(),
            'identifier' => $this->getIdentifier(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'icon' => $this->getIcon(),
            'extension' => $this->getExtension()->toArray(),
        ];
    }

	public function getSettings()
	{
		return [];
    }

    /**
     * @return ExtensionContract
     */
    public final function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param null $extension
     * @return BaseSlideType
     */
    public final function setExtension(ExtensionContract $extension)
    {
        $this->extension = $extension;
        return $this;
    }

    public function getDuration(SlideContract $slideContract)
    {
        return 0;
    }

    public function getFullScreen()
    {
        return false;
    }

    protected function registerOptionsForm(FormBuilder $form)
    {
        return null;
    }

    public function getOptionsForm()
    {
        $form = new FormBuilder();
        $view = $this->registerOptionsForm($form);

        if ($view && app()->bound('view')) {
            if ((is_string($view) && app('view')->exists($view)) || (is_array($view) && isset($view[1]) && is_array($view[1])) || $view instanceof \Illuminate\Contracts\View\View) {
                return $view;
            }
        }

        return json_decode(json_encode($form->getFields()));
    }

    protected function processList($list)
    {
        return collect($list)->values()->toArray();
    }

    public function getValidations()
    {
        return [
            'rules' => [],
            'messages' => []
        ];
    }

    public function isHidden()
    {
        return $this->hidden;
    }

    public function isVisible()
    {
        return !$this->isHidden();
    }

    public function slides()
    {
        return Slide::where('type', $this->getFullIdentifier());
    }

    protected function refreshSlides(array $filters)
    {
        $slides = Slide::where('type', $this->getFullIdentifier())->visible();
        foreach ($filters as $index => $filter)
        {
            $slides = $slides->where('options->'.$index,$filter);
        }
        Slide::updateSlides($slides->get());
        return response()->json('success',200);
    }

    protected function route($type, $uri, $callback) {
        $baseSlideType = $this;
        if(in_array($type,['get','post','delete','put','patch','options']))
        {
            app()->$type($baseSlideType->extension->getName().'/'.$uri, $callback);
        }
    }

    public function hasCorrectSettings($settings)
    {
        return true;
    }

    public function hasPadding()
    {
        return true;
    }

    public function getAttachedMedias(SlideContract $slide)
    {
        return [];
    }

    public function getAttachedFolders(SlideContract $slide)
    {
        return [];
    }

    public function displayOffline()
    {
        return false;
    }

    public function guessSlideName(SlideContract $slide)
    {
        return false;
    }

    public function neededExternalAccounts()
    {
        return [];
    }

    public function getAccountsByType($type)
    {
        return Account::accessible()->where("type", $type);
    }

    public function getAttachedRemoteFiles(SlideContract $slide)
    {
        return [];
    }
}
