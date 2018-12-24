<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use smart\menu\backend\assets\MenuFormAsset;
use smart\menu\models\Menu;

MenuFormAsset::register($this);

$typesWithName = Menu::getTypesWithName();
$typesWithUrl = Menu::getTypesWithUrl();
$typesWithAlias = Menu::getTypesWithAlias();

$typeOptions = [];
if ($model->getTypeDisabled()) {
    $typeOptions['disabled'] = true;
}

$nameOptions = [];
if (!in_array($model->type, $typesWithName)) {
    $nameOptions['options'] = ['class' => 'form-group d-none'];
}

$urlOptions = [];
if (!in_array($model->type, $typesWithUrl)) {
    $urlOptions['options'] = ['class' => 'form-group d-none'];
}

$aliasOptions = ['options' => [
    'data-url' => Url::toRoute('alias'),
    'class' => 'form-group',
]];
if (!in_array($model->type, $typesWithAlias)) {
    Html::addCssClass($aliasOptions['options'], 'd-none');
}

?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => [
        'data-types-with-name' => $typesWithName,
        'data-types-with-url' => $typesWithUrl,
        'data-types-with-alias' => $typesWithAlias,
    ],
]); ?>

    <?= $form->field($model, 'active')->checkbox() ?>
    <?= $form->field($model, 'type')->dropDownList(Menu::getTypeNames(), $typeOptions) ?>
    <?= $form->field($model, 'name', $nameOptions) ?>
    <?= $form->field($model, 'url', $urlOptions) ?>
    <?= $form->field($model, 'alias', $aliasOptions)->dropDownList($model->getAliasList()) ?>

    <div class="form-group form-buttons row">
        <div class="col-sm-12">
            <?= Html::submitButton(Yii::t('cms', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('cms', 'Cancel'), ['menu/index', 'id' => $id], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
