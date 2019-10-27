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
    public function map()
    {
        return [
            ['active', 'boolean'],
            [['name', 'alias'], 'string'],
        ];
    }
}
