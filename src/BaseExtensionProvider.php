<?php


namespace DynamicScreen\ExtensionKit;


use Illuminate\Support\ServiceProvider;

abstract class BaseExtensionProvider extends ServiceProvider
{
    protected $slideTypes;

    final public function register()
    {
        $this->registerExtension();
        $manager = app('extensionmanager');
        $manager->registerExtension($this);
    }

    public function registerExtension()
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

    final public function getSlideTypes()
    {
        return $this->slideTypes;
    }
}