<?php

namespace DynamicScreen\ExtensionKit\Warnings;

abstract class BaseWarning
{
    const VERY_LOW_PRIORITY = 7;
    const LOW_PRIORITY = 6;
    const NORMAL_PRIORITY = 5;
    const HIGH_PRIORITY = 4;
    const VERY_HIGH_PRIORITY = 3;
    const CRITICAL_PRIORITY = 2;

    protected $priority = self::NORMAL_PRIORITY;

    public abstract function objects($space_id);
    public abstract function view($object);
    public abstract function url($object);

    public final function priority()
    {
        return $this->priority;
    }
}