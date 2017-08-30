<?php
/**
 * Created by PhpStorm.
 * User: rembertin
 * Date: 04/02/17
 * Time: 17:02
 */

namespace DynamicScreen\ExtensionKit;


abstract class BaseWidget
{
    abstract public function getName();

    public function getDescription()
    {
        return '';
    }

    final public function getIdentifier() {
        return str_slug($this->getName());
    }

    public abstract function getComponentPath();
}