<?php

namespace smart\menu\backend\forms;

use Yii;
use smart\base\Form;
use smart\menu\models\Menu;

class ItemForm extends Form
{

    /**
     * @var boolean
     */
    public $active = 1;

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer type of menu item
     * @see smart\menu\models\Menu
     */
    public $type = Menu::SECTION;

    /**
     * @var string
     */
    public $url = '#';

    /**
     * @var string resource alias for some types of menu item
     */
    public $alias;

    /**
     * @var boolean
     */
    private $_typeDisabled = false;

    /**
     * @var array
     */
    private $_aliasList = [];

    /**
     * Type disabled getter
     * @return boolean
     */
    public function getTypeDisabled()
    {
        return $this->_typeDisabled;
    }

    /**
     * Alias list getter
     * @return array
     */
    public function getAliasList()
    {
        return $this->_aliasList;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'active' => Yii::t('menu', 'Active'),
            'name' => Yii::t('menu', 'Name'),
            'type' => Yii::t('menu', 'Type'),
            'url' => Yii::t('menu', 'Url'),
            'alias' => Yii::t('menu', 'Resource'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['active', 'boolean'],
            ['name', 'string', 'max' => 100],
            ['type', 'in', 'range' => Menu::getTypes()],
            ['url', 'string', 'max' => 200],
            ['alias', 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function assignFrom($object)
    {
        $this->active = $object->active == 0 ? '0' : '1';
        $this->name = $object->name;
        $this->type = $object->type;
        $this->url = $object->url;
        $this->alias = $object->alias;

        $this->_typeDisabled = $object->children()->count() > 0;
        $this->_aliasList = $object->getAliasList();
    }

    /**
     * @inheritdoc
     */
    public function assignTo($object)
    {
        $object->active = $this->active == 1;
        $object->name = $this->name;
        $object->type = $this->type;
        $object->url = $this->url;
        $object->alias = $this->alias;
    }

}
