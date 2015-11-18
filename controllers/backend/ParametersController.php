<?php

namespace cubiclab\store\controllers\backend;

use Yii;
use cubiclab\store\models\Parameters;
use cubiclab\store\models\search\ParametersSearch;
use cubiclab\admin\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ParametersController implements the CRUD actions for Parameters model.
 */
class ParametersController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'view'],
                'roles' => ['ACPParametersView']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['ACPParametersCreate']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['ACPParametersUpdate']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'mass-delete'],
            'roles' => ['ACPParametersDelete']
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
     * Lists all Parameters models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParametersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Parameters model.
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
     * Creates a new Parameters model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Parameters();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Parameters model.
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
            ]);
        }
    }

    /**
     * Deletes an existing Parameters model.
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
     * Finds the Parameters model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Parameters the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Parameters::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
