<?php

namespace cubiclab\store\controllers\backend;

use cubiclab\store\models\ProductsImages;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

use cubiclab\admin\components\Controller;

use cubiclab\store\models\Products;
use cubiclab\store\models\search\ProductsSearch;

class ProductsController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['ACPProductsView']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['ACPProductsCreate']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['ACPProductsUpdate']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'mass-delete'],
            'roles' => ['ACPProductsDelete']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['post', 'delete'],
                'mass-delete' => ['post', 'delete']
            ]
        ];
        return $behaviors;
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
        $product = new Products();
        $product_image = new ProductsImages();

        if ($product->load(Yii::$app->request->post())
            && $product->load_images(Yii::$app->request->post())
            && $product->load_parameters(Yii::$app->request->post('ParametersValues'))
            && $product->load_categories(Yii::$app->request->post('cat_tree'))
        ) {
            if ($product->validate()) {
                if ($product->save(false)) {
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
        $product_image = new ProductsImages();

        if ($product->load(Yii::$app->request->post())
            && $product->load_images(Yii::$app->request->post())
            && $product->load_parameters(Yii::$app->request->post('ParametersValues'))
            && $product->load_categories(Yii::$app->request->post('cat_tree'))
        ) {
            if ($product->validate()) {
                if ($product->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('storecube', 'PRODUCT_CREATE_SUCCESS'));
                    return $this->redirect(['update', 'id' => $product->id]);
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('storecube', 'PRODUCT_CREATE_FAIL'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($product);
            }
        } else {
            return $this->render('update', [
                'product' => $product,
                'product_image' => $product_image,
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
