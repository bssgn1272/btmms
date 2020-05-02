<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\assets\SharedUtils;

/**
 * OrderGoods is the model behind the orderGoods form.
 */
class MakeSale extends Model {

    public $buyerMsisdn;
    public $sellerMsisdn;
    public $amount;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['buyerMsisdn', 'sellerMsisdn', 'amount'], 'required'],
            [['buyerMsisdn', 'sellerMsisdn'], 'string', 'max' => 10],
            ['buyerMsisdn', 'validateBuyerMsisdn'],
            ['sellerMsisdn', 'validateSellerMsisdn'],
            ['amount', 'validateAmount'],
            ['amount', 'double'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'buyerMsisdn' => 'Buyer mobile number',
            'sellerMsisdn' => 'Your mobile number',
            'amount' => 'Total Sale Amount',
        ];
    }

    public function validateSellerMsisdn($attribute, $params) {
        $payload = SharedUtils::buildAPIRequest("", "", "", "", "", "", Yii::$app->params['country_code'] . $this->sellerMsisdn, "", "");
        $result = SharedUtils::httpGet("users", $payload, $this->sellerMsisdn);
        //  Yii::warning('validateSellerMsisdn| API Response is', var_export($result, true));
        if ($this->buyerMsisdn != $this->sellerMsisdn) {
            if (SharedUtils::validateMsisdn($this->sellerMsisdn) && !empty($result['users'])) {
                if ($result['users']['status'] === Yii::$app->params['ACC_BLOCKED_STATUS']) {
                    $this->addError('sellerMsisdn', 'Your account is blocked. Please see market administrator!');
                }
            } else {
                $this->addError('sellerMsisdn', 'This mobile number is not registered!');
            }
        } else {
            $this->addError('sellerMsisdn', 'You cannot sale goods to yourself. Please check this mobile number!');
        }
    }

    public function validateBuyerMsisdn($attribute, $params) {
        if (!SharedUtils::validateMsisdn($this->buyerMsisdn)) {
            $this->addError('buyerMsisdn', 'Invalid buyer mobile number!');
        }
    }

    public function validateAmount($attribute, $params) {
        if (!SharedUtils::validateAmount($this->amount)) {
            $this->addError('amount', 'Invalid amount entered!');
        }
    }

}
