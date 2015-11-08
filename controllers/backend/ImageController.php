<?php

namespace cubiclab\store\controllers\backend;


use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use cubiclab\admin\components\Controller;
use cubiclab\store\models\ProductsImages;
use yii\web\Response;

class ImageController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $image = $this->findModel(Yii::$app->request->post('key'));

            if ($image->delete()) {
                return true;
            }
        }
        return false;
    }

    protected function findModel($id)
    {
        if (($model = ProductsImages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
