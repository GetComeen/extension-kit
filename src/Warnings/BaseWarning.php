<?php

namespace DynamicScreen\ExtensionKit\Warnings;

abstract class BaseWarning
{
    public abstract function objects($space_id);
    public abstract function view($object);
    public abstract function url($object);
}