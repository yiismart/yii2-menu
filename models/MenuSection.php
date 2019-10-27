<?php

namespace smart\menu\models;

class MenuSection extends Menu
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->type = self::SECTION;
    }

    /**
     * @inheritdoc
     */
    public function isEnabled()
    {
        return true;
    }
}
