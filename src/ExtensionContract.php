<?php


namespace DynamicScreen\ExtensionKit;


interface ExtensionContract
{
    public function getIdentifier();

    public function setIdentifier($identifier);

    public function getName();

    public function setName($name);

    public function getLabel();

    public function setLabel($label);

    public function getAuthor();

    public function setAuthor($author);

    public function getVersion();

    public function setVersion($version);

    public function getSlideTypes();

    public function getWidgetTypes();

    public function setSlideTypes($slideTypes);

    public function setWidgetTypes($widgetTypes);

    public function getSlideType($identifier);

    public function getWidgetType($identifier);

    public function getAssetsPath();

    public function setAssetsPath($assetsPath);

    public function toArray();

    public function getStoragePath();

    public function uploadFile($name, $namespace = null);

    public function getUploadedFile($name, $namespace = null);

    public function getUploadedFileAbsolutePath($name, $namespace = null);
}