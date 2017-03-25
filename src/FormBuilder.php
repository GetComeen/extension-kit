<?php


namespace DynamicScreen\ExtensionKit;


class FormBuilder
{
    protected $fields = [];

    public function text($name, $label, $options = []) {
        return $this->add($name, $label, 'text', $options);
    }

    public function twitterInput($name, $label, $options = []) {
        return $this->add($name, $label, 'twitterInput', $options);
    }

    public function textarea($name, $label, $options = []) {
        return $this->add($name, $label, 'textarea', $options);
    }

    public function number($name, $label, $options = []) {
        return $this->add($name, $label, 'number', $options);
    }

    public function file($name, $label, $options = []) {
        return $this->add($name, $label, 'file', $options);
    }

    public function datepicker($name, $label, $options = []) {
        return $this->add($name, $label, 'datepicker', $options);
    }

    public function colorpicker($name, $label, $options = []) {
        return $this->add($name, $label, 'colorpicker', $options);
    }

    public function listElement($name, $label, $options = [])
    {
        return $this->add($name, $label, 'listElement', $options);
    }

    public function mapsAutocomplete($name, $label, $options = [])
    {
        return $this->add($name, $label, 'maps-autocomplete', $options);
    }

    /**
     * @param $name
     * @param $label
     * @param $options
     * @return $this
     */
    protected function add($name, $label, $type, $options = [])
    {
        $this->fields[$name] = compact('name', 'type', 'label', 'options');
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }
}