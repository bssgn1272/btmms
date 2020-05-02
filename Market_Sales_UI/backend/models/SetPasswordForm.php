<?php

namespace backend\models;

use yii\base\Model;
use yii\base\InvalidParamException;
use backend\models\User;

/**
 * Password reset form
 */
class SetPasswordForm extends Model {

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
    public function __construct($token, $config = []) {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password set token expired!.');
        }
        $this->_user = User::findByPasswordResetTokenInactiveAccount($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['password', 'string', 'min' => 8, 'message' => "password must have 8 characters minimum"],
            ['password', 'match', 'pattern' => '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', 'message' => 'Password must contain atleast a lower & upper case character, a special case and a digit'],
            ['password', 'required'],
            ['confirm_password', 'string', 'min' => 8],
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
        $user->setStatus();
        if (empty($this->_user->created_by) || empty($this->_user->updated_by)) {
            $user->setByIDs($this->_user->id);
        }
        $user->removePasswordResetToken();

        return $user->save(false);
    }

}
