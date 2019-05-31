<?php


namespace DynamicScreen\ExtensionKit;


interface AccountContract
{
    public function getName();
    public function getOptions();
    public function getOption($name, $default = null);
    public function getDriver();
}