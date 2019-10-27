<?php

namespace smart\menu\backend;

use Yii;
use yii\helpers\Html;
use smart\base\BackendModule;

class Module extends BackendModule
{
    /**
     * @inheritdoc
     */
    public static function security()
    {
        $auth = Yii::$app->getAuthManager();
        if ($auth->getRole('Menu') === null) {
            $role = $auth->createRole('Menu');
            $auth->add($role);
        }
    }

    /**
     * @inheritdoc
     */
    public function menu(&$items)
    {
        if (!Yii::$app->user->can('Menu')) {
            return;
        }

        $items['menu'] = [
            'label' => '<i class="fas fa-bars"></i> ' . Html::encode(Yii::t('menu', 'Menus')),
            'encode' => false,
            'url' => ['/menu/menu/index'],
        ];
    }
}
