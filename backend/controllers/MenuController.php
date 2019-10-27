<?php

namespace smart\menu\backend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use smart\base\BackendController;
use smart\menu\backend\filters\MenuFilter;
use smart\menu\backend\forms\MenuForm;
use smart\menu\models\Menu;

class MenuController extends BackendController
{
    /**
     * Tree
     * @param integer|null $id initial item id
     * @return string
     */
    public function actionIndex($id = null)
    {
        $model = new MenuFilter;
        $model->load(Yii::$app->getRequest()->get());

        return $this->render('index', [
            'model' => $model,
            'initial' => Menu::findOne($id),
        ]);
    }

    /**
     * Create
     * @return string
     */
    public function actionCreate()
    {
        $object = new Menu;
        $model = new MenuForm;

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            $success = $object->getIsNewRecord() ? $object->makeRoot() : $object->save();
            if ($success) {
                Yii::$app->session->setFlash('success', Yii::t('cms', 'Changes saved successfully.'));
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
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
        if ($object === null) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        $model = new MenuForm;
        $model->assignFrom($object);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            if ($object->save()) {
                Yii::$app->session->setFlash('success', Yii::t('cms', 'Changes saved successfully.'));
            }
            return $this->redirect(['index']);
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
        if ($object === null) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        if ($object->deleteWithChildren()) {
            Yii::$app->session->setFlash('success', Yii::t('cms', 'Item deleted successfully.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Move
     * @param integer $id 
     * @param integer $target 
     * @param integer $position 
     * @return void
     */
    public function actionMove($id, $target, $position)
    {
        $object = Menu::findOne($id);
        if ($object === null) {
            throw new BadRequestHttpException(Yii::t('menu', 'Item not found.'));
        }
        if ($object->isRoot()) {
            return;
        }

        $t = Menu::findOne($target);
        if ($t === null) {
            throw new BadRequestHttpException(Yii::t('menu', 'Item not found.'));
        }
        $tIsRoot = $t->isRoot();

        switch ($position) {
            case 0:
                if (!$tIsRoot)
                    $object->insertBefore($t);
                break;

            case 1:
                if ($t->type == Menu::SECTION)
                    $object->appendTo($t);
                break;

            case 2:
                if (!$tIsRoot)
                    $object->insertAfter($t);
                break;
        }
    }
}
