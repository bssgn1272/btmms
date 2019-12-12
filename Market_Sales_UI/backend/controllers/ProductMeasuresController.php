<?php

namespace backend\controllers;

use Yii;
use backend\models\ProductMeasures;
use backend\models\ProductMeasuresSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use backend\models\User;

/**
 * ProductMeasuresController implements the CRUD actions for ProductMeasures model.
 */
class ProductMeasuresController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProductMeasures models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new ProductMeasures();
        $searchModel = new ProductMeasuresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 15,];
        if (Yii::$app->request->post('hasEditable')) {
            $unit_id = Yii::$app->request->post('editableKey');
            $model = ProductMeasures::findOne($unit_id);
            $out = Json::encode(['output' => '', 'message' => '']);
            $posted = current($_POST['ProductMeasures']);
            $msg = "";
            $post = ['ProductMeasures' => $posted];
            if ($model->load($post) && $model->validate()) {
// can save model or do something before saving model
                $model->date_modified = new \yii\db\Expression('NOW()');
                $success = $model->save();
                $errors = $model->getErrors();
                $output = '';
                $message = '';

                if (!$success) {
                    foreach ($errors as $error) {
                        $message .= $error[0];
                    }
                }
                $out = Json::encode(['output' => $output, 'message' => $message]);
            }
            return $out;
        }
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }

    /**
     * Displays a single ProductMeasures model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProductMeasures model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if (User::userIsAllowedTo('Manage product measures')) {
            $model = new ProductMeasures();
            if (Yii::$app->request->isAjax) {
                $model->load(Yii::$app->request->post());
                return Json::encode(\yii\widgets\ActiveForm::validate($model));
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Unit of measure was successfully added.");
                    return $this->redirect(['index']);
                } else {
                    $errors = $model->getErrors();
                    $message = '';
                    foreach ($errors as $error) {
                        $message .= $error[0];
                    }
                    Yii::$app->session->setFlash('error', "Unit of measure could not added. Please try again! ERROR:: $message");
                    return $this->redirect(['index']);
                }
            }
            return $this->render('create', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'You are not authorised to perform that action.');
            return $this->redirect(['site/home']);
        }
    }

    /**
     * Updates an existing ProductMeasures model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        if (User::userIsAllowedTo('Manage product measures')) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->unit_of_measure_id]);
            }

            return $this->render('update', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'You are not authorised to perform that action.');
            return $this->redirect(['site/home']);
        }
    }

    /**
     * Deletes an existing ProductMeasures model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        if (User::userIsAllowedTo('Manage product measures')) {
            $model = $this->findModel($id);
            $model->delete();
            Yii::$app->session->setFlash('success', "Unit of measure: " . $model->unit_name . " was successfully deleted.");
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'You are not authorised to perform that action.');
            return $this->redirect(['site/home']);
        }
    }

    /**
     * Finds the ProductMeasures model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductMeasures the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ProductMeasures::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
