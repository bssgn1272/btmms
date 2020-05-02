<?php

namespace backend\controllers;

use Yii;
use backend\models\MarketChargeCollections;
use backend\models\MarketChargeCollectionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * MarketChargeCollectionsController implements the CRUD actions for MarketChargeCollections model.
 */
class MarketChargeCollectionsController extends Controller {

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
     * Lists all MarketChargeCollections models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MarketChargeCollections();
        $searchModel = new MarketChargeCollectionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 10,];
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MarketChargeCollections model.
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
     * Creates a new MarketChargeCollections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   /* public function actionCreate() {
        if (\backend\models\User::userIsAllowedTo('add market charge collection')) {
            $model = new MarketChargeCollections();
            if (Yii::$app->request->isAjax) {
                $model->load(Yii::$app->request->post());
                return Json::encode(\yii\widgets\ActiveForm::validate($model));
            }
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            if ($model->load(Yii::$app->request->post())) {
                $model->status = 0;
                $user_model = \backend\models\User::findOne(['user_id' => Yii::$app->user->identity->user_id]);
                if ($model->transaction_type == "DR") {
                    $model->amount = "-" . $model->amount;
                }

                if (!empty($user_model)) {
                    $model->created_by = $user_model->firstname . " " . $user_model->lastname . " - " . $user_model->email;
                    $model->modified_by = $user_model->firstname . " " . $user_model->lastname . " - " . $user_model->email;
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Market charge has been added successfully");
                    return $this->redirect(['index']);
                } else {
                    $errors = $model->getErrors();
                    $message = '';
                    foreach ($errors as $error) {
                        $message .= $error[0];
                    }
                    Yii::$app->session->setFlash('error', "Market charge could not be added. please try again!. Error:$message");
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
    }*/

    /**
     * Updates an existing MarketChargeCollections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   /* public function actionUpdate($id) {
        if (\backend\models\User::userIsAllowedTo('Update market charge collection status')) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                $user_model = \backend\models\User::findOne(['user_id' => Yii::$app->user->identity->user_id]);
                if (!empty($user_model)) {
                    $model->modified_by = $user_model->firstname . " " . $user_model->lastname . " - " . $user_model->email;
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Market charge transaction status has been updated successfully");
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $errors = $model->getErrors();
                    $message = '';
                    foreach ($errors as $error) {
                        $message .= $error[0];
                    }
                    Yii::$app->session->setFlash('error', "Market charge transaction status could not be updated. please try again!. Error:$message");
                }
            }

            return $this->render('update', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'You are not authorised to perform that action.');
            return $this->redirect(['site/home']);
        }
    }
*/
    /**
     * Deletes an existing MarketChargeCollections model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /* public function actionDelete($id) {
      $this->findModel($id)->delete();

      return $this->redirect(['index']);
      } */

    /**
     * Finds the MarketChargeCollections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MarketChargeCollections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MarketChargeCollections::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
