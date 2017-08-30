<?php


namespace DynamicScreen\ExtensionKit;


class FormBuilder
{
    protected $fields = [];

    public function text($name, $label, $options = []) {
        $options = array_merge(['label' => $label], $options);
        return $this->add($name, 'text', $options);
    }

    public function twitterInput($name, $label, $options = []) {
        $options = array_merge(['label' => $label], $options);
        return $this->add($name, 'twitterInput', $options);
    }

    public function textarea($name, $label, $options = []) {
        $options = array_merge(['label' => $label], $options);
        return $this->add($name, 'textarea', $options);
    }

    public function number($name, $label, $options = []) {
        $options = array_merge(['label' => $label], $options);
        return $this->add($name, 'number', $options);
    }

    public function file($name, $label, $options = []) {
        $options = array_merge(['label' => $label], $options);
        return $this->add($name, 'file', $options);
    }

    public function datepicker($name, $label, $options = []) {
        $options = array_merge(['label' => $label], $options);
        return $this->add($name, 'datepicker', $options);
    }

    public function colorpicker($name, $label, $options = []) {
        $options = array_merge(['label' => $label], $options);
        return $this->add($name, 'colorpicker', $options);
    }

    public function dropdown($name, $label, $dropdown_options, $options = []) {
        $options = array_merge(['label' => $label, 'options' => $dropdown_options], $options);
        return $this->add($name, 'select', $options);
    }

    public function list($name, $label, \Closure $form_builder,$options = [])
    {
        $form = new FormBuilder();
        $form_builder($form);
        $form = $form->getFields();
        $options = array_merge(compact('label', 'form'), $options);
        return $this->add($name, 'list', $options);
    }

    public function mapsAutocomplete($name, $label, $options = [])
    {
        $options = array_merge(['label' => $label], $options);
        return $this->add($name, 'maps-autocomplete', $options);
    }

    /**
     * @param $name
     * @param $type
     * @param array $options
     * @return $this
     */
    protected function add($name, $type, $options = [])
    {
        $this->fields[$name] = compact('name', 'type', 'options');
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }
}