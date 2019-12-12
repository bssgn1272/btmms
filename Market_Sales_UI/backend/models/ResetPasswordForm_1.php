<?php

namespace backend\models;

use yii\base\Model;
use yii\base\InvalidParamException;
use backend\models\User;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm_1 extends Model {

    public $password;
    public $confirm_password;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($config = []) {

        $this->_user = User::findById(Yii::$app->user->identity->id);
        if (!$this->_user) {
            throw new InvalidParamException('Fatal error occured!.');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'string', 'min' => 6],
            ['confirm_password', 'required'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords do not match!"]
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword() {
        $user = $this->_user;
        $user->setPassword($this->password);
        return $user->save(false);
    }

}
