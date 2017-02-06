<?php


namespace DynamicScreen\ExtensionKit;


interface ExtensionContract
{
    public function getIdentifier();

    public function setIdentifier($identifier);

    public function getName();

    public function setName($name);

    public function getAuthor();

    public function setAuthor($author);

    public function getVersion();

    public function setVersion($version);

    public function getSlideTypes();

    public function setSlideTypes($slideTypes);

    public function getSlideType($identifier);

    public function getAssetsPath();

    public function setAssetsPath($assetsPath);

    public function toArray();
}