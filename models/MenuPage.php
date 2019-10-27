<?php

namespace smart\menu\models;

use smart\page\models\Page;

class MenuPage extends Menu
{
    const OBJECT_CLASS = 'smart\page\models\Page';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->type = self::PAGE;
    }

    /**
     * @inheritdoc
     */
    public function isEnabled()
    {
        return class_exists(self::OBJECT_CLASS);
    }

    /**
     * @inheritdoc
     */
    public function isAliasNeeded()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getAliasList()
    {
        $class = self::OBJECT_CLASS;
        $items = [];
        foreach ($class::find()->select(['alias', 'title'])->asArray()->all() as $row) {
            $items[$row['alias']] = $row['title'];
        }

        return $items;
    }

    /**
     * @inheritdoc
     */
    public function createUrl()
    {
        return ['/page/page/index', 'alias' => $this->alias];
    }
}
