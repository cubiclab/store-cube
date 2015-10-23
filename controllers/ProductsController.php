<?php

namespace cubiclab\store\controllers;

use cubiclab\store\models\ProductsImages;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;

use cubiclab\admin\components\Controller;

use cubiclab\store\models\Categories;
use cubiclab\store\models\Parameters;
use cubiclab\store\models\ParametersRange;
use cubiclab\store\models\ParametersValues;

use cubiclab\store\models\Products;
use cubiclab\store\models\ProductsSearch;

class ProductsController extends Controller
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

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //продукт
        $product = new Products();
        //$product->scenario = 'insert';

        $product_image = new ProductsImages();
        $product_image->scenario = 'insert';

        //имена параметров
        $parameters = new Parameters();
        $parameters = $parameters->find()->all();
        $param_values = '';//new ParametersValues();

        //категории
        $categories = new Categories();
        $categories = $categories->find()->all();

        $post = Yii::$app->request->post();


        if ($product->load(Yii::$app->request->post()) && $product->load_images(Yii::$app->request->post())){
            if ($product->validate()) {
                if ($product->save(false)) {

                    //сохраняем параметры
                    foreach (Yii::$app->request->post('ParametersValues') as $parameter_key => $parameter_value) {
                        if ($parameter_value) {
                            $parameter_name = Parameters::findOne($parameter_key);
                            switch ($parameter_name->is_range) {
                                case ParametersRange::RANGE_SINGLE;
                                    $new_parameter_value = new ParametersValues();
                                    $new_parameter_value->param_id = $parameter_key;
                                    $new_parameter_value->product_id = $product->id;
                                    $new_parameter_value->range_id = $range_id;
                                    $new_parameter_value->param_value = "";
                                    $new_parameter_value->save();
                                    break;

                                case ParametersRange::RANGE_MULTIPLY;
                                    foreach ($parameter_value as $range_key => $range_id) {
                                        $new_parameter_value = new ParametersValues();
                                        $new_parameter_value->param_id = $parameter_key;
                                        $new_parameter_value->product_id = $product->id;
                                        $new_parameter_value->range_id = $range_id;
                                        $new_parameter_value->param_value = "";
                                        $new_parameter_value->save();
                                    }

                                default: //Parameters::RANGE_NULL;
                                    $new_parameter_value = new ParametersValues();
                                    $new_parameter_value->param_id = $parameter_key;
                                    $new_parameter_value->product_id = $product->id;
                                    $new_parameter_value->param_value = $parameter_value;
                                    $new_parameter_value->save();
                                    break;
                            }
                        }
                    }

                    Yii::$app->session->setFlash('success', Yii::t('storecube', 'PRODUCT_CREATE_SUCCESS'));
                    return $this->redirect(['update', 'id' => $product->id]);
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('storecube', 'PRODUCT_CREATE_FAIL'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                // TODO: доделать
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($product);
            }
        } else {
            return $this->render('create', [
                'product' => $product,
                'product_image' => $product_image,
                'parameter_names' => $parameters,
                'param_values' => $param_values,
            ]);
        }
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $product = $this->findModel($id);
        //имена параметров
        $parameters = new Parameters();
        $parameters = $parameters->find()->all();


        $product_image = new ProductsImages();
        //$product_image->scenario = 'update';

        //значения параметров
        $param_values = ParametersValues::findAll(['product_id' => $id]);

        if ($product->load(Yii::$app->request->post())) {
            if ($product->validate()) {
                if ($product->save(false)) {
                    foreach ($param_values as $param_value) {
                        $param_value->delete();
                    }

                    //сохраняем параметры
                    foreach (Yii::$app->request->post('ParametersValues') as $parameter_key => $parameter_value) {
                        if ($parameter_value) {
                            $parameter_name = Parameters::findOne($parameter_key);
                            switch ($parameter_name->is_range) {
                                case ParametersRange::RANGE_SINGLE;
                                    $new_parameter_value = new ParametersValues();
                                    $new_parameter_value->param_id = $parameter_key;
                                    $new_parameter_value->product_id = $product->id;
                                    $new_parameter_value->range_id = $range_id;
                                    $new_parameter_value->param_value = "";
                                    $new_parameter_value->save();
                                    break;

                                case ParametersRange::RANGE_MULTIPLY;
                                    foreach ($parameter_value as $range_key => $range_id) {
                                        $new_parameter_value = new ParametersValues();
                                        $new_parameter_value->param_id = $parameter_key;
                                        $new_parameter_value->product_id = $product->id;
                                        $new_parameter_value->range_id = $range_id;
                                        $new_parameter_value->param_value = "";
                                        $new_parameter_value->save();
                                    }

                                default: //Parameters::RANGE_NULL;
                                    $new_parameter_value = new ParametersValues();
                                    $new_parameter_value->param_id = $parameter_key;
                                    $new_parameter_value->product_id = $product->id;
                                    $new_parameter_value->param_value = $parameter_value;
                                    $new_parameter_value->save();
                                    break;
                            }
                        }
                    }

                    Yii::$app->session->setFlash('success', Yii::t('storecube', 'PRODUCT_CREATE_SUCCESS'));
                    return $this->redirect(['update', 'id' => $product->id]);
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('storecube', 'PRODUCT_CREATE_FAIL'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                // TODO: доделать
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($product);
            }
        } else {
            return $this->render('update', [
                'product' => $product,
                'product_image' => $product_image,
                'parameter_names' => $parameters,
                'param_values' => $param_values,
            ]);
        }
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
