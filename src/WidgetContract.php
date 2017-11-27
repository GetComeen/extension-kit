<?php


namespace DynamicScreen\ExtensionKit;


interface WidgetContract
{
    public function getName();
    public function getOptions();
    public function getDisplay();
    public function getExtensionSettings();
}