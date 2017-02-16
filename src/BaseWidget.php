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

    public abstract function getComponentPath();
}