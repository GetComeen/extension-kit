<?php

namespace DynamicScreen\ExtensionKit;

use App\DataSources\DataSet;
use App\Models\Account;
use App\Models\DataSource;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class DataSourceDefinition
{
     /**
     * @var ExtensionContract
     */
    protected $extension = null;

    protected $scheduled = false;

    abstract public function getName();
    abstract public function getAuthorName();
    abstract public function getIcon();
    abstract public function fetch(DataSource $source, ?Request $request) : ?DataSet;
    abstract public function renderOptions(DataSource $source) : View;

    public function getIdentifier()
    {
        return $this->getName();
    }

    public function getFullIdentifier()
    {
        return $this->getExtension()
            ? $this->extension->getIdentifier() . '.' . $this->getIdentifier()
            : $this->getIdentifier();
    }

    public function getColor()
    {
        return '#239d00';
    }

    public function getDescription()
    {
        return '';
    }

    public function dataType()
    {
        return TableData::class;
    }

    public function getDefaultOptions()
    {
        return [];
    }

    public function processOptions(array $options)
    {
        return $options;
    }

    public final function isScheduled()
    {
        return $this->scheduled;
    }

    public function isSchedulable()
    {
        return $this->scheduled;
    }

    public function validationRules($creating)
    {
        return [];
    }

    public function getValidations()
    {
        return [
            'rules' => [],
            'messages' => []
        ];
    }

    public function validationCustomMessages()
    {
        return [];
    }

    public function validationCustomAttributes()
    {
        return [];
    }

    public function neededExternalAccounts()
    {
        return [];
    }

    public function getAccountsByType($type)
    {
<<<<<<< HEAD
        return Account::accessible()->where("type", $type);
=======
        return Account::where(['type' => $type, 'space_id' => current_space()->id, 'active' => true]);
>>>>>>> feature/lumapps
    }

    public function hasCorrectSettings($settings)
    {
        return true;
    }

    /**
     * @param null $extension
     * @return DatasourceDefinition
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    public final function getExtension()
    {
        return $this->extension;
    }

}
