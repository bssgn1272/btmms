<?php

namespace backend\controllers;

use Yii;
use backend\models\Roles;
use backend\models\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RolesController extends Controller {

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
     * Lists all Roles models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new Roles();
        $searchModel = new RolesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 15,];
        if (Yii::$app->request->post('hasEditable')) {
            $role_id = Yii::$app->request->post('editableKey');
            $model = Roles::findOne($role_id);
            $out = Json::encode(['output' => '', 'message' => '']);
            $posted = current($_POST['Roles']);
            $msg = "";
            //Yii::warning('POOOOOOOOOOOOOOOOOOOOOOOOOOOSSSSSSSSSSSSST DATTTTTTTTTTTTTTTTTTTTAAAA', var_export($posted,true));
            $post = ['Roles' => $posted];
            if ($model->load($post)) {
                $model->updated_by = Yii::$app->user->identity->user_id;
                $model->date_updated = new \yii\db\Expression('NOW()');
                $model->permissions = [""];
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
     * Displays a single Roles model.
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
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        if (\backend\models\User::userIsAllowedTo('Manage Roles')) {
            $model = new Roles();
            if (Yii::$app->request->isAjax) {
                $model->load(Yii::$app->request->post());
                return Json::encode(\yii\widgets\ActiveForm::validate($model));
            }

            if ($model->load(Yii::$app->request->post())) {
                if (!empty($model->permissions)) {
                    $model->created_by = Yii::$app->user->identity->user_id;
                    $model->updated_by = Yii::$app->user->identity->user_id;
                    // $model->active = 1;
                    //  $model->save();
                    // var_dump($model->errors);
                    if ($model->save()) {

                        foreach ($model->permissions as $permission) {
                            $permToRoleModel = new \backend\models\PermissionsToRoles();
                            // $permToGroupModel->id = 0; //primary key(auto increment id) id
                            // $permToRoleModel->isNewRecord = true;
                            $permToRoleModel->permission_id = $permission;
                            $permToRoleModel->role_id = $model->role_id;
                            $permToRoleModel->validate();
                            $permToRoleModel->save(false);
                        }
                        Yii::$app->session->setFlash('success', 'Role ' . $model->name . ' was successfully created.');
                        return $this->redirect(['index']);
                    } else {
                        Yii::$app->session->setFlash('error', "Role could not be created. Please try again!");
                        return $this->redirect(['index']);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'You need to select atleast one role permission!');
                    return $this->render('create', ['model' => $model,]);
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
     * Updates an existing Roles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        if (\backend\models\User::userIsAllowedTo('Manage Roles')) {

            $model = $this->findModel($id);
            if (Yii::$app->request->isAjax) {
                $model->load(Yii::$app->request->post());
                return Json::encode(\yii\widgets\ActiveForm::validate($model));
            }
            $model->permissions = \backend\models\PermissionsToRoles::getRolePermissions($model->role_id);
            $model->date_updated = new \yii\db\Expression('NOW()');

            $array = [];
            foreach ($model->permissions as $perm => $v) {
                array_push($array, $perm);
            }
            $model->permissions = $array;
            if ($model->load(Yii::$app->request->post())) {
                // $model->active = 1;

                if (!empty($model->permissions)) {

                    if ($model->save()) {
                        $permToRoleModel = new \backend\models\PermissionsToRoles();
                        $permToRoleModel::deleteAll(['role_id' => $model->role_id]);

                        foreach ($model->permissions as $permission) {
                            //$permToRoleModel->id = 0; //primary key(auto increment id) id
                            //  $permToRoleModel->isNewRecord = true;
                            $permToRoleModel = new \backend\models\PermissionsToRoles();
                            $permToRoleModel->permission_id = $permission;
                            $permToRoleModel->role_id = $model->role_id;
                            $permToRoleModel->validate();
                            $permToRoleModel->save();
                        }
                        //check if current user has the role that has just been edited so that we update the permissions instead of user logging out
                        if (Yii::$app->getUser()->identity->role->role_id == $model->role_id) {
                            $rightsArray = \backend\models\PermissionsToRoles::getRolePermissions(Yii::$app->getUser()->identity->role->role_id);
                            $rights = implode(",", $rightsArray);
                            //Now lets check special permissions
                            /* $specialPerms = \backend\models\PermissionsToUsers::getSpecialPerms(Yii::$app->user->identity->user_id);
                              if (!empty($specialPerms)) {
                              foreach ($specialPerms as $value) {
                              $rights .= "," . $value;
                              }
                              }
                              $specialRolePerms = \backend\models\RoleToUsers::getRolePermissions(Yii::$app->user->identity->user_id);
                              if (!empty($specialRolePerms)) {
                              foreach ($specialRolePerms as $value) {
                              $rights .= "," . $value;
                              }
                              } */
                            $session = Yii::$app->session;
                            $session->set('rights', $rights);
                        }
                        Yii::$app->session->setFlash('success', 'Role ' . $model->name . ' was successfully updated.');
                        return $this->redirect(['index']);
                    } else {
                        $errors = $model->getErrors();

                        $output = '';
                        $message = '';

                        foreach ($errors as $error) {
                            $message .= $error[0];
                        }

                        Yii::$app->session->setFlash('error', 'Error occured while updating role.Please try again.ERROR:' . $message);
                        return $this->render('update', ['id' => $model->role_id,]);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'You need to select atleast one group permission!');
                    return $this->render('update', ['id' => $model->role_id,]);
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

    /**
     * Deletes an existing Roles model.
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
     * Finds the Roles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Roles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Roles::findOne(["role_id" => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
