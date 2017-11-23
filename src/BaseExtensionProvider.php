<?php


namespace DynamicScreen\ExtensionKit;


use Illuminate\Support\ServiceProvider;
use App\Models\Slide;
use App\Models\Widget;
use \Illuminate\Support\Facades\Route;

abstract class BaseExtensionProvider extends ServiceProvider
{
    private $slideTypes = [];
    private $widgetTypes = [];
    private $viewPath = null;

    final public function register()
    {
        $this->registerExtension();
        if (config('dynamicscreen.app') == 'display') {
            $this->registerExtensionInDisplay();
        } elseif (config('dynamicscreen.app') == 'core') {
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
    abstract public function getExtensionLabel();


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
            $this->widgetTypes[$widgetType->getIdentifier()] = $widgetType;
        }
    }

    final public function getSlideTypes()
    {
        return $this->slideTypes;
    }

    final public function getWidgetTypes()
    {
        return $this->widgetTypes;
    }

    final public function getWidgets()
    {
        return $this->widgets;
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
        $slideTypes = array_keys($this->getSlideTypes());
        $slideTypesFullName = array_map(function($query) {
            return $this->getExtensionIdentifier().'.'.$query;
        },$slideTypes);
        $slides = Slide::whereIn('type', $slideTypesFullName);
        foreach ($filters as $index => $filter)
        {
            $slides = $slides->where('options->'.$index,$filter);
        }
        Slide::updateSlides($slides->get());
        return response()->json('success',200);
    }

    protected function refreshWidgets(array $filters)
    {
        $widgetTypes = array_keys($this->getWidgetTypes());
        $widgetTypesFullName = array_map(function($query) {
            return $this->getExtensionIdentifier().'.'.$query;
        },$widgetTypes);
        $widgets = Widget::where('type', $widgetTypesFullName);
        Widget::updateWidgets($widgets->get());
        return response()->json('success',200);
    }

    protected function route($type, $uri, $callback) {
        $baseExtension = $this;
        if(in_array($type,['get','post','delete','put','patch','options']))
        {
            Route::$type($baseExtension->getExtensionName().'/'.$uri, $callback);
        }
    }

    public function getSettingsView($settings)
    {
        return null;
    }

    public function getSettingsRules()
    {
        return [];
    }

    public function processSettings($request)
    {
        return $request->all();
    }
}