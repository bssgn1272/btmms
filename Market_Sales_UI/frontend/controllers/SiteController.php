<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\OrderGoods;
use frontend\models\MakeSale;
use yii\helpers\Json;
use frontend\assets\SharedUtils;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
     * @return mixed
     */
    public function actionIndex() {
        $session = Yii::$app->session;
        $session->destroy();
        return $this->render('index');
    }

    public function actionOrderGoods() {
        $model = new OrderGoods();
        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            return Json::encode(\yii\widgets\ActiveForm::validate($model));
        }
        if ($model->load(Yii::$app->request->post())) {
            $payload = SharedUtils::buildAPIRequest("", "", "", "", "", "", Yii::$app->params['country_code'] . $model->supplierMsisdn, "", "");
            $result = SharedUtils::httpGet("users", $payload, $model->supplierMsisdn);
            if (!empty($result['users'])) {
                $session = Yii::$app->session;
                $session->set('supplierMobileNumber', $model->supplierMsisdn);
                $session->set('buyerMobileNumber', $model->buyerMsisdn);
                $session->set('amount', $model->amount);
                $session->set('seller_details', $result['users']);
                $session->set('seller_names', $result['users']['firstname'] . " " . $result['users']['lastname']);
                return $this->redirect(['confirm-order-goods']);
            } else {
                Yii::$app->session->setFlash('error', "Error occured while ordering goods.Please try again!");
            }
        }
        return $this->render('order-goods', [
                    'model' => $model,
        ]);
    }

    public function actionConfirmOrderGoods() {

        if (Yii::$app->request->post()) {
            //Lets push the transaction to the api
            $_msg = "";
            $session = Yii::$app->session;
            $payload = SharedUtils::buildPushTransactionRequest($session->get('seller_details')['trader_id'], $session->get('amount'), Yii::$app->params['country_code'] . $session->get("buyerMobileNumber"));
            \Yii::warning('actionConfirmOrderGoods| API request is', var_export($payload, true));
            $result = SharedUtils::httpPostJson("transactions", $payload, Yii::$app->params['country_code'] . $session->get("buyerMobileNumber"));

            if (!$result['error']) {
                // var_dump($result['error']);
                //Yii::$app->session->setFlash('success', "Transaction is being processed. You will soon receive a confirmation SMS");
                $_msg = "Transaction is being processed. You will soon receive a confirmation SMS";
                return $this->redirect(['index', '_msg' => $_msg]);
            } else {
                Yii::$app->session->setFlash('error', "Error occured while processing transaction.Please try again!" . $result['error']);
                return $this->redirect(['order-goods']);
            }
        }

        return $this->render('confirm-order-goods', [
                        //  'model' => $model,
        ]);
    }

    public function actionSale() {
        $model = new MakeSale();
        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            return Json::encode(\yii\widgets\ActiveForm::validate($model));
        }
        if ($model->load(Yii::$app->request->post())) {
            $payload = SharedUtils::buildAPIRequest("", "", "", "", "", "", Yii::$app->params['country_code'] . $model->sellerMsisdn, "", "");
            $result = SharedUtils::httpGet("users", $payload, $model->sellerMsisdn);
            if (!empty($result['users'])) {
                $session = Yii::$app->session;
                $session->set('supplierMobileNumber', $model->sellerMsisdn);
                $session->set('buyerMobileNumber', $model->buyerMsisdn);
                $session->set('amount', $model->amount);
                $session->set('seller_details', $result['users']);
                $session->set('seller_names', $result['users']['firstname'] . " " . $result['users']['lastname']);
                return $this->redirect(['confirm-make-sale']);
            } else {
                Yii::$app->session->setFlash('error', "Error occured while making a sale.Please try again!");
            }
        }
        return $this->render('sale', [
                    'model' => $model,
        ]);
    }

    public function actionConfirmMakeSale() {

        if (Yii::$app->request->post()) {
            //Lets push the transaction to the api
            $_msg = "";
            $session = Yii::$app->session;
            $payload = SharedUtils::buildPushTransactionRequest($session->get('seller_details')['trader_id'], $session->get('amount'), Yii::$app->params['country_code'] . $session->get("buyerMobileNumber"));
            \Yii::warning('actionConfirmOrderGoods| API request is', var_export($payload, true));
            $result = SharedUtils::httpPostJson("transactions", $payload, Yii::$app->params['country_code'] . $session->get("buyerMobileNumber"));

            if (!$result['error']) {
                // var_dump($result['error']);
                //Yii::$app->session->setFlash('success', "Transaction is being processed. You will soon receive a confirmation SMS");
                $_msg = "Transaction is being processed. You will soon receive a confirmation SMS";
                return $this->redirect(['index', '_msg' => $_msg]);
            } else {
                Yii::$app->session->setFlash('error', "Error occured while processing transaction.Please try again!" . $result['error']);
                return $this->redirect(['sale']);
            }
        }

        return $this->render('confirm-make-sale', [
                        //  'model' => $model,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('site/login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
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
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token) {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail() {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
                    'model' => $model
        ]);
    }

}
