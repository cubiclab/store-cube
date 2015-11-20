<?php

namespace cubiclab\store\controllers\backend;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cubiclab\admin\components\Controller;
use cubiclab\store\models\PriceTypes;
use cubiclab\store\models\search\PriceTypesSearch;
use cubiclab\store\models\NsiCurrency;
use cubiclab\store\models\NsiCurrencySymbol;

/**
 * PriceTypesController implements the CRUD actions for PriceTypes model.
 */
class PriceTypesController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'view'],
                'roles' => ['ACPPriceTypesView']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['ACPPriceTypesCreate']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['ACPPriceTypesUpdate']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'mass-delete'],
            'roles' => ['ACPPriceTypesDelete']
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
     * Lists all PriceTypes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PriceTypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statusArray' => PriceTypes::getStatusArray(),
        ]);
    }

    /**
     * Displays a single PriceTypes model.
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
     * Creates a new PriceTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PriceTypes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'currencyCodeArray' => NsiCurrency::getCurrencyCodeArray(),
                'currencySymbolArray' => NsiCurrencySymbol::getCurrencySymbolArray(),
                'statusArray' => PriceTypes::getStatusArray(),
            ]);
        }
    }

    /**
     * Updates an existing PriceTypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'currencyCodeArray' => NsiCurrency::getCurrencyCodeArray(),
                'currencySymbolArray' => NsiCurrencySymbol::getCurrencySymbolArray(),
                'statusArray' => PriceTypes::getStatusArray(),
            ]);
        }
    }

    /**
     * Deletes an existing PriceTypes model.
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
     * Finds the PriceTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PriceTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PriceTypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
