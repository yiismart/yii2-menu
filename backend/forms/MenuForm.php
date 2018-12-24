<?php

namespace smart\menu\backend\forms;

use Yii;
use smart\base\Form;

class MenuForm extends Form
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
     * @var string
     */
    public $alias;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'active' => Yii::t('menu', 'Active'),
            'name' => Yii::t('menu', 'Name'),
            'alias' => Yii::t('menu', 'Alias'),
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
            ['alias', 'string', 'max' => 200],
            ['alias', 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function assignFrom($object)
    {
        $this->active = $object->active == 0 ? '0' : '1';
        $this->name = $object->name;
        $this->alias = $object->alias;
    }

    /**
     * @inheritdoc
     */
    public function assignTo($object)
    {
        $object->active = $this->active == 1;
        $object->name = $this->name;
        $object->alias = $this->alias;
    }

    /**
     * Save object using model attributes
     * @return boolean
     */
    public function save()
    {
        if (!$this->validate())
            return false;

        $object = $this->_object;

        $object->active = $this->active == 1;
        $object->name = $this->name;
        $object->alias = $this->alias;

        if ($object->getIsNewRecord()) {
            if (!$object->makeRoot(false))
                return false;
        } else {
            if (!$object->save(false))
                return false;
        }

        return true;
    }

}
