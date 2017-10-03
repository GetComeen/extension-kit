<?php


namespace DynamicScreen\ExtensionKit;


use Illuminate\Support\ServiceProvider;

abstract class BaseExtensionProvider extends ServiceProvider
{
    private $slideTypes = [];
    private $widgetTypes = [];
    private $viewPath = null;
    private $script = null;

    final public function register()
    {
        $this->registerExtension();
        if (config('dynamicscreen.app') == 'display') {
            $this->registerExtensionInDisplay();
        } elseif (config('dynamicscreen.app') == 'core-api') {
            $this->registerExtensionInApi();
        }

        $this->registerViewPath();
        $manager = app('extensionmanager');
        $manager->registerExtension($this);

    }

    public function registerExtension()
    {

    }

    public function registerExtensionInApi()
    {
        
    }

    public function registerExtensionInDisplay()
    {

    }

    public function getExtensionIdentifier()
    {
        return str_slug($this->getExtensionAuthor()) . '.' . str_slug($this->getExtensionName());
    }

    abstract public function getExtensionName();
    abstract public function getExtensionAuthor();

    public function getExtensionVersion()
    {
        return '1.0';
    }

    public function getAssetsPath()
    {
        return './';
    }

    final protected function registerSlideType($className)
    {
        $slideType = new $className;
        if ($slideType instanceof BaseSlideType) {
            $this->slideTypes[$slideType->getIdentifier()] = $slideType;
        }
    }

    final protected function registerWidgetType($className)
    {
        $widgetType = new $className;
        if ($widgetType instanceof BaseWidgetType) {
            if (config('dynamicscreen.app') == 'display') {
                $widgetType->registerSlideInDisplay();
            } elseif (config('dynamicscreen.app') == 'core-api') {
                $widgetType->registerSlideInApi();
            }
            $this->widgetTypes[$widgetType->getIdentifier()] = $widgetType;
        }
    }

    final public function getSlideTypes()
    {
        return $this->slideTypes;
    }

    final public function getWidgetTypes() {
        return $this->widgetTypes;
    }

    protected function setViewPath($path)
    {
        $this->viewPath = $path;
    }

    private function registerViewPath()
    {
        if ($this->viewPath === null) {
            return;
        }

        $this->loadViewsFrom($this->viewPath, $this->getExtensionIdentifier());
    }

    protected function schedule($scheduler)
    {
        
    }

    public function getScriptFile()
    {
        return null;
    }

    protected function refreshSlides(array $filters)
    {
        $slides = Slide::where('type', $this->getFullIdentifier());
        foreach ($filters as $index => $filter)
        {
            $slides = $slides->where('options->'.$index,$filter);
        }
        return $slides->get();
    }
}