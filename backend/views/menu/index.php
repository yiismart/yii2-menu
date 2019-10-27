<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use dkhlystov\widgets\NestedTreeGrid;
use smart\menu\models\Menu;

// Title
$title = Yii::t('menu', 'Menus');
$this->title = $title . ' | ' . Yii::$app->name;

// Breadcrumbs
$this->params['breadcrumbs'] = [
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<p class="form-buttons">
    <?= Html::a(Yii::t('cms', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
</p>

<?= NestedTreeGrid::widget([
    'dataProvider' => $model->getDataProvider(),
    'showRoots' => true,
    'initialNode' => $initial,
    'moveAction' => ['move'],
    'pluginOptions' => [
        'onMoveOver' => new JsExpression('function (item, helper, target, position) {
            if (item.data("root") == 1) return false;
            if (position != 1 && target.data("root") == 1) return false;
            return position != 1 || target.data("type") == 0;
        }'),
    ],
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options = ['data-type' => $model->type, 'data-root' => $model->isRoot() ? 1 : 0];
        if (!$model->active) {
            Html::addCssClass($options, 'table-warning');
        }
        return $options;
    },
    'columns' => [
        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function ($model, $key, $index, $column) {
                $value = Html::encode($model->name);
                if ($model->isRoot()) {
                    $value .= ' ' . Html::tag('span', Html::encode($model->alias), ['class' => 'badge badge-primary']);
                } elseif ($model->type == Menu::LINK) {
                    $value .= ' ' . Html::tag('span', Html::encode($model->url), ['class' => 'text-info']);
                } elseif ($model->type != Menu::SECTION) {
                    $type = Html::tag('span', Html::encode($model->getTypeName()), ['class' => 'badge badge-secondary']);

                    if ($model->type == Menu::DIVIDER) {
                        $value = $type;
                    } else {
                        $value .= ' ' . $type;
                    }
                }

                return $value;
            }
        ],
        [
            'class' => 'smart\grid\ActionColumn',
            'options' => ['style' => 'width: 78px;'],
            'template' => '{update} {delete} {create}',
            'buttons' => [
                'create' => function ($url, $model, $key) {
                    $isSection = $model->type == Menu::SECTION;
                    if (!($model->isRoot() || $isSection)) {
                        return '';
                    }
                    $title = Yii::t('menu', 'Create item');
                    return Html::a('<span class="fas fa-plus"></span>', $url, [
                        'title' => $title,
                        'aria-label' => $title,
                        'data-pjax' => 0,
                    ]);
                },
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action == 'create' || !$model->isRoot()) {
                    $action = 'item/' . $action;
                }
                return [$action, 'id' => $model->id];
            },
        ],
    ],
]) ?>
