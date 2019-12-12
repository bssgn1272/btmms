<?php

namespace backend\controllers;

use Yii;
use backend\models\Products;
use backend\models\ProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use backend\models\User;
use yii\web\UploadedFile;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller {

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
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new Products();
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 15,];
        if (Yii::$app->request->post('hasEditable')) {
            $product_id = Yii::$app->request->post('editableKey');
            $model = Products::findOne($product_id);
            $file = $model->product_image;
            $model->scenario = 'update';
            $out = Json::encode(['output' => '', 'message' => '']);
            $posted = current($_POST['Products']);
            $msg = "";
            $post = ['Products' => $posted];
            if ($model->load($post) && $model->validate()) {
                // can save model or do something before saving model
                $model->date_modified = new \yii\db\Expression('NOW()');
                if (!empty($file)) {
                    unlink(Yii::getAlias('@app') . '/web/uploads/products/' . $file);
                }
                $imageFile = UploadedFile::getInstancesByName('Products');
                if (!empty($imageFile)) {
                    foreach ($imageFile as $images => $image) {
                        $imageFilename = Yii::$app->security->generateRandomString() . '.' . $image->extension;
                        $model->product_image = $imageFilename;
                        $image->saveAs(Yii::getAlias('@app') . '/web/uploads/products/' . $imageFilename);
                    }
                }
                // $success = $model->save();
                $success = $model->save();
                $errors = $model->getErrors();
                //Yii::warning('SAVVVVVVVVVVVVVVVVVVVVVVEEEEEEE ERRRRRRRRRRRRRORS::', var_export($errors));
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
     * Displays a single Products model.
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
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if (User::userIsAllowedTo('Manage products')) {
            $model = new Products();
            if (Yii::$app->request->isAjax) {
                $model->load(Yii::$app->request->post());
                return Json::encode(\yii\widgets\ActiveForm::validate($model));
            }
            if ($model->load(Yii::$app->request->post())) {
                $imageFile = UploadedFile::getInstance($model, 'product_image');
                if (!empty($imageFile)) {
                    $imageFilename = Yii::$app->security->generateRandomString() . '.' . $imageFile->extension;
                    $model->product_image = $imageFilename;
                    $imageFile->saveAs(Yii::getAlias('@app') . '/web/uploads/products/' . $imageFilename);
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Product was successfully added.");
                    return $this->redirect(['index']);
                } else {
                    $errors = $model->getErrors();
                    $message = '';
                    foreach ($errors as $error) {
                        $message .= $error[0];
                    }
                    Yii::$app->session->setFlash('error', "Product was not added. Please try again! ERROR:: $message");
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

    public function updateImg($model) {
        if (Yii::$app->request->post('hasEditable')) {
            
        }
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        if (User::userIsAllowedTo('Manage products')) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->product_id]);
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
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        if (User::userIsAllowedTo('Manage products')) {
            $model = $this->findModel($id);
            unlink(Yii::getAlias('@app') . '/web/uploads/products/' . $model->product_image);
            $model->delete();
            Yii::$app->session->setFlash('success', "Product: " . $model->product_name . " was successfully deleted.");
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'You are not authorised to perform that action.');
            return $this->redirect(['site/home']);
        }
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
