<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "market_notifications".
 *
 * @property int $id
 * @property string $type
 * @property string $message
 * @property string $recipients
 * @property int $status
 * @property string $notification_date
 */
class MarketNotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'market_notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type','message', 'status', 'notification_date'], 'required'],
            [['recipients'], 'string'],
            [['status'], 'integer'],
            [['notification_date'], 'safe'],
            [['type', 'message'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'message' => 'Message',
            'recipients' => 'Recipients',
            'status' => 'Status',
            'notification_date' => 'Notification Date',
        ];
    }
}
