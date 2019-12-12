<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\assets\SharedUtils;

/**
 * OrderGoods is the model behind the orderGoods form.
 */
class OrderGoods extends Model {

    public $buyerMsisdn;
    public $supplierMsisdn;
    public $amount;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['buyerMsisdn', 'supplierMsisdn', 'amount'], 'required'],
            [['buyerMsisdn', 'supplierMsisdn'], 'string', 'max' => 10],
            ['buyerMsisdn', 'validateBuyerMsisdn'],
            ['supplierMsisdn', 'validateSupplierMsisdn'],
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
            'supplierMsisdn' => 'Supplier mobile number',
            'amount' => 'Total Sale Amount',
        ];
    }

    public function validateSupplierMsisdn($attribute, $params) {
        $payload = SharedUtils::buildAPIRequest("", "", "", "", "", "", Yii::$app->params['country_code'] . $this->supplierMsisdn, "", "");
        $result = SharedUtils::httpGet("users", $payload, $this->supplierMsisdn);
      //  Yii::warning('validateSupplierMsisdn| API Response is', var_export($result, true));
        if ($this->buyerMsisdn != $this->supplierMsisdn) {
            if (SharedUtils::validateMsisdn($this->supplierMsisdn) && !empty($result['users'])) {
                if ($result['users']['status'] === Yii::$app->params['ACC_BLOCKED_STATUS']) {
                    $this->addError('supplierMsisdn', 'This supplier mobile number is blocked. Supplier should see market administrator!');
                }
            } else {
                $this->addError('supplierMsisdn', 'Supplier mobile number is not registered!');
            }
        } else {
            $this->addError('supplierMsisdn', 'You cannot order goods from yourself. Please check supplier number!');
        }
    }

    public function validateBuyerMsisdn($attribute, $params) {
        $payload = SharedUtils::buildAPIRequest("", "", "", "", "", "", Yii::$app->params['country_code'] . $this->buyerMsisdn, "", "");
        $result = SharedUtils::httpGet("users", $payload, $this->buyerMsisdn);
       // Yii::warning('validateBuyerMsisdn| API Response is', var_export($result, true));
        if (SharedUtils::validateMsisdn($this->buyerMsisdn) && !empty($result['users'])) {
            if ($result['users']['status'] === Yii::$app->params['ACC_BLOCKED_STATUS']) {
                $this->addError('buyerMsisdn', 'Your mobile number is blocked. Please see market administrator!');
            }
        } else {
            $this->addError('buyerMsisdn', 'Mobile number is not registered!');
        }
    }

    public function validateAmount($attribute, $params) {
        if (!SharedUtils::validateAmount($this->amount)) {
            $this->addError('amount', 'Invalid amount entered!');
        }
    }

}
