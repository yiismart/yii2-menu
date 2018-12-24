<?php

use yii\helpers\Html;

// Title
$title = $object->name;
$this->title = $title . ' | ' . Yii::$app->name;

// Breadcrumbs
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('menu', 'Menus'), 'url' => ['menu/index']],
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', [
    'model' => $model,
    'id' => $object->id,
]) ?>
