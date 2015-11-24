<?php

namespace cubiclab\store\controllers\backend;

use cubiclab\store\models\DapTerms;
use Yii;
use cubiclab\store\models\Orders;
use cubiclab\store\models\search\OrdersSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cubiclab\admin\components\Controller;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'view'],
                'roles' => ['ACPOrdersView']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['ACPOrdersCreate']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['ACPOrdersUpdate']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'mass-delete'],
            'roles' => ['ACPOrdersDelete']
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
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $statusArray = Orders::getStatusArray();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statusArray' => $statusArray,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $statusArray = Orders::getStatusArray();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'statusArray' => $statusArray,
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        $dap = DapTerms::find()->where(['status'=>DapTerms::STATUS_ACTIVE])->all();
        $deliveryArray = [];
        $paymentArray = [];
        foreach($dap as $row){
            if($row->type == DapTerms::TYPE_DELIVERY){
                $deliveryArray[$row->id] = $row->name;
            } elseif ($row->type == DapTerms::TYPE_PAYMENT){
                $paymentArray[$row->id] = $row->name;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'paymentArray' => $paymentArray,
                'deliveryArray' => $deliveryArray,
                'statusArray' => Orders::getStatusArray(),
            ]);
        }
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $dap = DapTerms::find()->where(['status'=>DapTerms::STATUS_ACTIVE])->all();
        $deliveryArray = [];
        $paymentArray = [];
        foreach($dap as $row){
            if($row->type == DapTerms::TYPE_DELIVERY){
                $deliveryArray[$row->id] = $row->name;
            } elseif ($row->type == DapTerms::TYPE_PAYMENT){
                $paymentArray[$row->id] = $row->name;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'paymentArray' => $paymentArray,
                'deliveryArray' => $deliveryArray,
                'statusArray' => Orders::getStatusArray(),
            ]);
        }
    }

    /**
     * Deletes an existing Orders model.
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
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
