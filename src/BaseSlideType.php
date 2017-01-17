<?php


namespace DynamicScreen\ExtensionKit;


abstract class BaseSlideType
{
    abstract public function getName();

    public function getDescription()
    {
        return '';
    }

    public function getIcon()
    {
        return 'square';
    }
}