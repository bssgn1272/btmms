<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {

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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new User();
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 15,];
        if (Yii::$app->request->post('hasEditable')) {
            $user_id = Yii::$app->request->post('editableKey');
            $model = User::findOne($user_id);
            $out = Json::encode(['output' => '', 'message' => '']);
            $posted = current($_POST['User']);
            $msg = "";
            //Yii::warning('POOOOOOOOOOOOOOOOOOOOOOOOOOOSSSSSSSSSSSSST DATTTTTTTTTTTTTTTTTTTTAAAA', var_export($posted,true));
            $post = ['User' => $posted];
            if ($model->load($post)) {
                $model->updated_by = Yii::$app->user->identity->user_id;
                // can save model or do something before saving model
                $success = $model->save();
                $errors = $model->getErrors();

                $output = '';
                $message = '';

                if (!$success) {
                    foreach ($errors as $error) {
                        // Yii::warning('POOOOOOOOOOOOOOOOOOOOOOOOOOOSSSSSSSSSSSSST DATTTTTTTTTTTTTTTTTTTTAAAA', var_export($error, true));
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
                    'model' => $model,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if (User::userIsAllowedTo('Manage Users')) {
            $model = new User();
            if (Yii::$app->request->isAjax) {
                $model->load(Yii::$app->request->post());
                return Json::encode(\yii\widgets\ActiveForm::validate($model));
            }
            if ($model->load(Yii::$app->request->post())) {
                //$model->username = $model->email;
                $model->status = User::STATUS_INACTIVE;
                $model->auth_key = Yii::$app->security->generateRandomString();
                //Temp password hash 
                $model->password = Yii::$app->getSecurity()->generatePasswordHash($model->mobile_number . $model->auth_key);
                $model->created_by = Yii::$app->user->identity->user_id;
                $model->updated_by = Yii::$app->user->identity->user_id;
                $model->token_balance = 0;

                if ($model->validate() && $model->save()) {
                    $resetPasswordModel = new \backend\models\PasswordResetRequestForm();
                    if ($resetPasswordModel->sendEmailAccountCreation($model->email)) {
                        Yii::$app->session->setFlash('success', 'User account with username:' . $model->email . ' was successfully created.');
                        return $this->redirect(['index']);
                    } else {
                        Yii::$app->session->setFlash('error', "User account created but email not sent!");
                        return $this->redirect(['index']);
                    }
                } else {
                    $errors = $model->getErrors();

                    $output = '';
                    $message = '';

                    foreach ($errors as $error) {
                        $message .= $error[0];
                    }

                    Yii::$app->session->setFlash('error', "Error occured while creating user with username $model->email.Please try again. ERROR:: $message");
                    return $this->redirect(['index']);
                }
            }
        } else {
            Yii::$app->session->setFlash('error', 'You are not authorised to perform that action.');
            return $this->redirect(['site/home']);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        if (User::userIsAllowedTo('Manage Users')) {
            $model = $this->findModel($id);
            if (Yii::$app->request->isAjax) {
                $model->load(Yii::$app->request->post());
                return Json::encode(\yii\widgets\ActiveForm::validate($model));
            }
            if ($model->load(Yii::$app->request->post())) {
                $model->date_updated = new \yii\db\Expression('NOW()');
                $model->updated_by = Yii::$app->user->identity->user_id;
                if ($model->validate() && $model->save()) {
                    Yii::$app->session->setFlash('success', 'User details were successfully updated.');
                    return $this->redirect(['view', 'id' => $model->user_id]);
                } else {
                    $errors = $model->getErrors();

                    $output = '';
                    $message = '';

                    if (!$success) {
                        foreach ($errors as $error) {
                            $message .= $error[0];
                        }
                    }
                    Yii::$app->session->setFlash('error', "Error occured while updating user details.Error is:" . $message);
                    return $this->render('update', ['id' => $model->user_id,]);
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

    public function actionProfile($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Profile successfully updated.');
                return $this->redirect(['profile', 'id' => $model->user_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Error occured while updating profile.Please try again!');
                return $this->redirect(['profile', 'id' => $model->user_id]);
            }
        }
        return $this->render('profile', [
                    'model' => $model,
        ]);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionImage($id, $type = "") {
        $model = \backend\models\Image::findOne(['user_id' => $id]);
        if (!empty($model)) {
            $file = $model->file;
            if ($model->load(Yii::$app->request->post())) {
                unlink(Yii::getAlias('@backend') . '/web/uploads/profile/' . $file);
                $imageFile = UploadedFile::getInstance($model, 'file');
                if (!empty($imageFile)) {
                    $imageFilename = Yii::$app->security->generateRandomString() . '.' . $imageFile->extension;
                    $model->file = $imageFilename;
                    $imageFile->saveAs(Yii::getAlias('@backend') . '/web/uploads/profile/' . $imageFilename);
                }
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', 'User profile picture successfully updated!');
                } else {
                    Yii::$app->session->setFlash('error', 'User profile picture could not be updated!');
                }
            }
        } else {
            $model = new \backend\models\Image();
            if ($model->load(Yii::$app->request->post())) {
                $imageFile = UploadedFile::getInstance($model, 'file');
                if (!empty($imageFile)) {
                    $imageFilename = Yii::$app->security->generateRandomString() . '.' . $imageFile->extension;
                    $model->file = $imageFilename;
                    $model->user_id = $id;
                    $imageFile->saveAs(Yii::getAlias('@backend') . '/web/uploads/profile/' . $imageFilename);
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Successfully uploaded user profile picture!');
                } else {
                    Yii::$app->session->setFlash('error', 'User profile picture could not be uploaded!');
                }
                //return $this->redirect(['index']);
            }
        }
        if (!empty($type)) {
            return $this->render('profile', [
                        'model' => $this->findModel($id),
            ]);
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
