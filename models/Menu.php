<?php

namespace smart\menu\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class Menu extends ActiveRecord
{
    const SECTION = 0;
    const DIVIDER = 1;
    const LINK = 2;
    const PAGE = 3;
    // const GALLERY = ;
    // const CONTACT = ;
    // const NEWS = ;
    // const REVIEW = ;
    // const FEEDBACK = ;
    // const CATALOG = ;

    private static $_typeNames = [
        self::SECTION => 'Section',
        self::DIVIDER => 'Divider',
        self::LINK => 'Link',
        self::PAGE => 'Page',
        // self::GALLERY => 'Gallery',
        // self::CONTACT => 'Contacts',
        // self::NEWS => 'News',
        // self::REVIEW => 'Reviews',
        // self::FEEDBACK => 'Feedback',
        // self::CATALOG => 'Catalog',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        switch ($row['type']) {
            case self::SECTION:
                return new MenuSection;
            case self::DIVIDER:
                return new MenuDivider;
            case self::LINK:
                return new MenuLink;
            case self::PAGE:
                return new MenuPage;
            // case self::GALLERY:
            //     return new MenuGallery;
            // case self::CONTACT:
            //     return new MenuContact;
            // case self::NEWS:
            //     return new MenuNews;
            // case self::REVIEW:
            //     return new MenuReview;
            // case self::FEEDBACK:
            //     return new MenuFeedback;
            // case self::CATALOG:
            //     return new MenuCatalog;
            default:
                return new static;
        }
    }

    /**
     * Available type names getter
     * @return array
     */
    public static function getTypeNames()
    {
        $names = [];
        foreach (self::$_typeNames as $type => $name) {
            $object = self::instantiate(['type' => $type]);
            if ($object->isEnabled()) {
                $names[$type] = Yii::t('menu', $name);
            }
        }
        return $names;
    }

    /**
     * Available types
     * @return array
     */
    public static function getTypes()
    {
        return array_keys(self::getTypeNames());
    }

    /**
     * Getter for types where name is needed
     * @return array
     */
    public static function getTypesWithName()
    {
        $types = [];
        foreach (self::$_typeNames as $type => $name) {
            $object = self::instantiate(['type' => $type]);
            if ($object->isNameNeeded()) {
                $types[] = $type;
            }
        }
        return $types;
    }

    /**
     * Getter for types where url is needed
     * @return array
     */
    public static function getTypesWithUrl()
    {
        $types = [];
        foreach (self::$_typeNames as $type => $name) {
            $object = self::instantiate(['type' => $type]);
            if ($object->isUrlNeeded()) {
                $types[] = $type;
            }
        }
        return $types;
    }

    /**
     * Getter for types where alias is needed
     * @return array
     */
    public static function getTypesWithAlias()
    {
        $types = [];
        foreach (self::$_typeNames as $type => $name) {
            $object = self::instantiate(['type' => $type]);
            if ($object->isAliasNeeded()) {
                $types[] = $type;
            }
        }
        return $types;
    }

    /**
     * Available aliases getter for type
     * @param integer $type 
     * @return array
     */
    public static function getAliasListByType($type)
    {
        $object = self::instantiate(['type' => $type]);
        return $object->getAliasList();
    }

    /**
     * Find by alias
     * @param sring $alias Alias or id
     * @return static
     */
    public static function findByAlias($alias) {
        $model = static::findOne(['alias' => $alias]);
        if ($model === null)
            $model = static::findOne(['id' => $alias]);

        return $model;
    }

    /**
     * Type name getter
     * @return string
     */
    public function getTypeName()
    {
        return Yii::t('menu', ArrayHelper::getValue(self::$_typeNames, $this->type, ''));
    }

    /**
     * Is this type enabled
     * @return boolean
     */
    public function isEnabled()
    {
        return false;
    }

    /**
     * Is the name needed for this type
     * @return boolean
     */
    public function isNameNeeded()
    {
        return true;
    }

    /**
     * Is the url needed for this type
     * @return boolean
     */
    public function isUrlNeeded()
    {
        return false;
    }

    /**
     * Is the alias needed for this type
     * @return boolean
     */
    public function isAliasNeeded()
    {
        return false;
    }

    /**
     * Available aliases getter
     * @return array
     */
    public function getAliasList()
    {
        return [];
    }

    /**
     * Url creator
     * @return string
     */
    public function createUrl()
    {
        return '#';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new MenuQuery(get_called_class());
    }
}

class MenuQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}
