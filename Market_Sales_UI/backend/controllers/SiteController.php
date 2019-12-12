<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use backend\models\LoginForm;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use yii\helpers\Json;
use backend\models\User;
use backend\models\UserSearch;

class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'home', 'changePassword'],
                'rules' => [
                    [
                        'actions' => ['logout', 'home', 'changePassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $this->layout = 'login';
       //  Yii::$app->user->logout();
        return $this->redirect(['login']);
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $role_model = Yii::$app->getUser()->identity->role;
            $rightsArray = \backend\models\PermissionsToRoles::getRolePermissions($role_model->role_id);
            $rights = implode(",", $rightsArray);
            //Now lets check special permissions
           /* $specialPerms = \backend\models\PermissionsToUsers::getSpecialPerms(Yii::$app->user->identity->id);
            if (!empty($specialPerms)) {
                foreach ($specialPerms as $value) {
                    $rights .= "," . $value;
                }
            }
            $specialRolePerms = \backend\models\RoleToUsers::getRolePermissions(Yii::$app->user->identity->id);
            if (!empty($specialRolePerms)) {
                foreach ($specialRolePerms as $value) {
                    $rights .= "," . $value;
                }
            }*/
            $session = Yii::$app->session;
            $session->set('rights', $rights);
            return $this->redirect(['site/home']);
        }

        $model->password = '';
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    public function actionHome() {
        $this->layout = 'main';
        
            return $this->render('home', [
            ]);
      
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

    public function actionChangePassword() {
        $this->layout = 'main';
        $model = new \backend\models\ResetPasswordForm_1();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->user->logout();
            Yii::$app->session->setFlash('success', 'Password was successfully changed. Sign in with your new password');
            return $this->goHome();
        }

        return $this->render('changePassword', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionRequestPasswordReset() {

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }
        $this->layout = 'login';
        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionResetPassword1($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }
        $this->layout = 'login';
        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    public function actionSetPassword($token) {
        try {
            $this->layout = 'login';
            $model = new \backend\models\SetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Account was successfully activated. Login into your account!');
            $model = new LoginForm();
            return $this->goHome();
        }

        return $this->render('setPassword', [
                    'model' => $model,
        ]);
    }

    public function actionResetPassword($token) {
        try {
            $this->layout = 'login';
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Password was successfully reset. Sign in with your new password');

            $model = new LoginForm();
            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

}
