<?php


namespace DynamicScreen\ExtensionKit;

// use App\DataSources\DataSourceDefinition;
use DynamicScreen\ExtensionKit\DataSourceDefinition;
use Illuminate\Support\ServiceProvider;
use App\Models\Slide;
use App\Models\Widget;
use \Illuminate\Support\Facades\Route;

abstract class BaseExtensionProvider extends ServiceProvider
{
    private $slideTypes = [];
    private $widgetTypes = [];
    private $triggerDefinitions = [];
    private $viewPath = null;
    private $basePath = '../';
    private $publishedAssets = [];
    private $defaultDisplayMetadata = [];
    private $datasourceDefinitions = [];

    final public function boot()
    {
        $this->registerExtension();
        if (config('dynamicscreen.app') == 'display') {
            $this->registerExtensionInDisplay();
        } elseif (config('dynamicscreen.app') == 'core') {
            $this->registerExtensionInApi();
        }

        $manager = app('extensionmanager');
        $manager->registerExtension($this);
        $this->bootExtension();
    }

    final public function register()
    {
        $basedir = $this->getExtensionPath();
        $manifext_file = $basedir . '/manifext.json';
        if ($path = realpath($manifext_file)) {
            $this->manifextPath = $path;
        } else {
            $manifext_file = $basedir . '/extension.json';
            if ($path = realpath($manifext_file)) {
                $this->manifextPath = $path;
            } else {
                $this->manifextPath = null;
            }
            $this->manifextPath = null;
        }

        $this->registerViewPath();
    }

    public function registerExtension()
    {

    }

    public function bootExtension()
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

    /**
     * @return string
     */
    public function getExtensionPath()
    {
        return realpath(dirname(with(new \ReflectionClass($this))->getFileName()) . str_start($this->basePath, '/'));
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

    final protected function registerTriggerDefinition($className)
    {
        $definition = new $className;
        if ($definition instanceof TriggerDefinition) {
            $this->triggerDefinitions[$definition->getIdentifier()] = $definition;
        }
    }

    final public function getTriggerDefinitions()
    {
        return $this->triggerDefinitions;
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
        $slideTypesFullName = array_map(function ($query) {
            return $this->getExtensionIdentifier() . '.' . $query;
        }, $slideTypes);
        $slides = Slide::whereIn('type', $slideTypesFullName);
        foreach ($filters as $index => $filter) {
            $slides = $slides->where('options->' . $index, $filter);
        }
        Slide::updateSlides($slides->get());
        return response()->json('success', 200);
    }

    protected function refreshWidgets(array $filters)
    {
        $widgetTypes = array_keys($this->getWidgetTypes());
        $widgetTypesFullName = array_map(function ($query) {
            return $this->getExtensionIdentifier() . '.' . $query;
        }, $widgetTypes);
        $widgets = Widget::where('type', $widgetTypesFullName);
        Widget::updateWidgets($widgets->get());
        return response()->json('success', 200);
    }

    protected function route($type, $uri, $callback)
    {
        $baseExtension = $this;
        if (in_array($type, ['get', 'post', 'delete', 'put', 'patch', 'options'])) {
            Route::$type($baseExtension->getExtensionName() . '/' . $uri, $callback);
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

    /**
     * @return string
     */
    public function getManifextPath()
    {
        return $this->manifextPath;
    }

    private final function endpointBase()
    {
        return \Route::domain('api.' . config('dynamicscreen.domain'))
                     ->middleware([
                         'api',
//                         'cors',
                     ])
                     ->prefix('/' . urlencode($this->getExtensionIdentifier()))
                     ->name($this->getExtensionIdentifier() . '.');
    }

    protected function apiGet($uri, $action)
    {
        return $this->endpointBase()->get($uri, $action);
    }

    protected function apiPost($uri, $action)
    {
        return $this->endpointBase()->post($uri, $action);
    }

    protected function publish($from, $to)
    {
        $this->publishedAssets[$from] = $to;
    }

    public function publishAssets()
    {
        $this->publishes(collect($this->publishedAssets)->mapWithKeys(function ($to, $from) {
            return [$this->getExtensionPath() . str_start($from, '/') => public_path('assets/' . $this->getExtensionIdentifier() . str_start($to, '/'))];
        })->toArray(), 'display.public');
    }

    public function needsAdditionalSettings()
    {
        return true;
    }

    protected final function registerDefaultDisplayMetadata($key) : DefaultDisplayMetadata
    {
        $metadata = new DefaultDisplayMetadata($key);
        $this->defaultDisplayMetadata[] = $metadata;
        return $metadata;
    }

    public final function getDefaultDisplayMetadata()
    {
        return $this->defaultDisplayMetadata;
    }

    protected final function registerDatasourceDefinitions($className): DatasourceDefinition
    {
        $datasourceDefinition = new $className;
        if ($datasourceDefinition instanceof DataSourceDefinition) {
            $this->datasourceDefinitions[$datasourceDefinition->getIdentifier()] = $datasourceDefinition;
        }
        return $datasourceDefinition;
    }

    public final function getDatasourceDefinitions()
    {
        return $this->datasourceDefinitions;
    }


}
