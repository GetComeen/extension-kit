<?php

namespace DynamicScreen\ExtensionKit;


abstract class BaseWidgetType
{
    private $widget_buffer = [];
    /**
     * @var ExtensionContract
     */
    protected $extension = null;
    protected $hidden = false;

    abstract public function getName();
    abstract public function fetchWidget(WidgetContract $slide);

    public function registerSlideInDisplay() {}
    public function registerSlideInApi() {}

    final public function getIdentifier() {
        return str_slug($this->getName());
    }

    public final function getFullIdentifier()
    {
        return $this->extension->getIdentifier() . '.' . $this->getIdentifier();
    }

    public function getDescription()
    {
        return '';
    }

    public function getColor()
    {
        return '#239d00';
    }

    public function getIcon()
    {
        return 'square';
    }

    public function getDefaultOptions()
    {
        return [];
    }

    public function processOptions($options)
    {
        return $options;
    }

    final protected function widget($data)
    {
        $this->widget_buffer[] = $data;
        return $this;
    }

    final public function flushWidgets()
    {
        $widgets = $this->widget_buffer;
        $this->widget_buffer = [];
        return $widgets;
    }

    final public function toArray()
    {
        return [
            'full_identifier' => $this->getFullIdentifier(),
            'identifier' => $this->getIdentifier(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'icon' => $this->getIcon(),
            'extension' => $this->getExtension()->toArray(),
        ];
    }

    public function getSettings()
    {
        return [];
    }

    /**
     * @return ExtensionContract
     */
    public final function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param null $extension
     * @return BaseSlideType
     */
    public final function setExtension(ExtensionContract $extension)
    {
        $this->extension = $extension;
        return $this;
    }

    protected function registerOptionsForm(FormBuilder $form)
    {

    }

    public function getOptionsForm()
    {
        $form = new FormBuilder();
        $this->registerOptionsForm($form);
        return json_decode(json_encode($form->getFields()));
    }

    protected function processList($list)
    {
        return collect($list)->values()->toArray();
    }

    public function getValidations()
    {
        return [
            'rules' => [],
            'messages' => []
        ];
    }

    public function isHidden()
    {
        return $this->hidden;
    }

    public function isVisible()
    {
        return !$this->isHidden();
    }

    public function hasCorrectSettings($settings)
    {
        return true;
    }

    public function neededExternalAccounts()
    {
        return [];
    }

    public abstract function getComponentPath();
}
