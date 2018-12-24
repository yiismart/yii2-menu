<?php

namespace smart\menu\backend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use smart\base\BackendController;
use smart\menu\backend\forms\ItemForm;
use smart\menu\models\Menu;

class ItemController extends BackendController
{

    /**
     * Create
     * @param integer $id 
     * @return string
     */
    public function actionCreate($id)
    {
        $parent = Menu::findOne($id);
        if ($parent === null) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        $object = new Menu;
        $model = new ItemForm;

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            if ($object->appendTo($parent)) {
                Yii::$app->session->setFlash('success', Yii::t('cms', 'Changes saved successfully.'));
            }
            return $this->redirect([
                'menu/index',
                'id' => $object->id,
            ]);
        }

        return $this->render('create', [
            'model' => $model,
            'id' => $id,
        ]);
    }

    /**
     * Update
     * @param integer $id
     * @return string
     */
    public function actionUpdate($id)
    {
        $object = Menu::findOne($id);
        if ($object === null || $object->isRoot()) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        $model = new ItemForm;
        $model->assignFrom($object);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            if ($object->save()) {
                Yii::$app->session->setFlash('success', Yii::t('cms', 'Changes saved successfully.'));
            }
            return $this->redirect([
                'menu/index',
                'id' => $object->id,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'object' => $object,
        ]);
    }

    /**
     * Delete
     * @param integer $id
     * @return string
     */
    public function actionDelete($id)
    {
        $object = Menu::findOne($id);
        if ($object === null || $object->isRoot()) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        $initial = $object->prev()->one();
        if ($initial === null) $initial = $object->next()->one();
        if ($initial === null) $initial = $object->parents(1)->one();

        if ($object->deleteWithChildren()) {
            Yii::$app->session->setFlash('success', Yii::t('cms', 'Item deleted successfully.'));
        }

        return $this->redirect([
            'menu/index',
            'id' => ArrayHelper::getValue($initial, 'id'),
        ]);
    }

    /**
     * Making alias list for specified menu item type
     * @param integer $type menu item type
     * @return string
     */
    public function actionAlias($type)
    {
        return Json::encode([
            'type' => (integer) $type,
            'items' => Menu::getAliasListByType($type),
        ]);
    }

}
