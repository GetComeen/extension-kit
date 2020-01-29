<?php

namespace DynamicScreen\ExtensionKit;

use App\MemoizeAwareTrait;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;

class DefaultDisplayMetadata
{
    use MemoizeAwareTrait;

    protected $key = "";
    protected $title = "";
    protected $description = "";
    protected $default_value = "";
    protected $linkedSlideType = "";
    protected $force = false;
    protected $admin = false;
    protected $validation_rules = [];
    protected $extension;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        if (Lang::has($this->title)) {
            return Lang::get($this->title);
        }
        return $this->title;
    }

    /**
     * @param string $title
     * @return DefaultDisplayMetadata
     */
    public function title(string $title): DefaultDisplayMetadata
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if (Lang::has($this->description)) {
            return Lang::get($this->description);
        }
        return $this->description;
    }

    /**
     * @param string $description
     * @return DefaultDisplayMetadata
     */
    public function description(string $description): DefaultDisplayMetadata
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        if (Lang::has($this->default_value)) {
            return Lang::get($this->default_value);
        }
        return $this->default_value;
    }

    /**
     * @param string $default_value
     * @return DefaultDisplayMetadata
     */
    public function default(string $default_value): DefaultDisplayMetadata
    {
        $this->default_value = $default_value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     * @return DefaultDisplayMetadata
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->validation_rules;
    }

    public function validation(...$validation_rules): DefaultDisplayMetadata
    {
        $this->validation_rules = $validation_rules;
        return $this;
    }

    /**
     * @return string
     */
    public function getLinkedSlideType(): string
    {
        return $this->linkedSlideType;
    }

    /**
     * @param string $linkedSlideType
     * @return DefaultDisplayMetadata
     */
    public function linkedSlideType($linkedSlideType): DefaultDisplayMetadata
    {
        $this->linkedSlideType = $linkedSlideType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isForced(): bool
    {
        return $this->force;
    }

    /**
     * @param bool $force
     * @return DefaultDisplayMetadata
     */
    public function force(bool $force = true): DefaultDisplayMetadata
    {
        $this->force = $force;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * @param bool $admin
     * @return DefaultDisplayMetadata
     */
    public function admin(bool $admin = true): DefaultDisplayMetadata
    {
        $this->admin = $admin;
        return $this;
    }

    public function getGroup()
    {
        return once(function () {
            if (!$this->extension) {
                return null;
            }

            if ($this->getLinkedSlideType()) {
                $type = $this->getExtension()->getSlideType($this->getLinkedSlideType());
                if ($type) {
                    return $type;
                }
            }

            return $this->getExtension();
        });
    }

    public function getGroupKey()
    {
        $group = $this->getGroup();
        $refl = new \ReflectionClass($group);
        if ($this->isAdmin()) {
            return "admin";
        } elseif ($refl->getName() === "DynamicScreen\ExtensionSupport\Extension") {
            return "extension::" . $group->getIdentifier();
        } elseif ($group instanceof BaseSlideType) {
            return "slide::" . $group->getFullIdentifier();
        }

        return "system";
    }

    public function getGroupTitle($forceIcon = false)
    {
        $group = $this->getGroup();
        $refl = new \ReflectionClass($group);
        if ($this->isAdmin()) {
            return new HtmlString("<i class=\"fa fa-fw fa-suitcase\"></i> " . __('app.administration'));
        } elseif ($refl->getName() === "DynamicScreen\ExtensionSupport\Extension") {
            $icon = $forceIcon ? "fa-puzzle-piece" : "";
            return new HtmlString("<i class=\"fa fa-fw {$icon}\"></i> {$group->getLabel()}");
        } elseif ($group instanceof BaseSlideType) {
            return new HtmlString("<i class=\"fa fa-fw {$group->getIcon()}\"></i> {$group->getName()}");
        }

        return new HtmlString("<i class=\"fa fa-fw fa-gear\"></i> Système");
    }

}