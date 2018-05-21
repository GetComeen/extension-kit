<?php


namespace DynamicScreen\ExtensionKit;


interface ScenarioContract
{
    public function getName();
    public function getOptions();
    public function getExtensionSettings();
    public function getOption($name, $default = null);
    public function run(array $data_tokens = []);
}