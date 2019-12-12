<?php

namespace backend\models;

use Yii;
use \yii\helpers\ArrayHelper;
/**
 * This is the model class for table "measures".
 *
 * @property int $unit_of_measure_id
 * @property string $unit_name
 * @property string $unit_description
 * @property string $date_created
 * @property string $date_modified
 */
class ProductMeasures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'measures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_name'], 'required'],
            [['date_created', 'date_modified'], 'safe'],
            [['unit_name'], 'string', 'max' => 20],
            [['unit_description'], 'string', 'max' => 500],
            //[['unit_name'], 'unique'],
            [['unit_name'], 'unique', 'message' => "Unit already exists!"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'unit_of_measure_id' => 'Unit Of Measure ID',
            'unit_name' => 'Unit Name',
            'unit_description' => 'Unit Description',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
        ];
    }
    
       public static function getUnitNames() {
        $measures = static::find()->orderBy(['unit_of_measure_id' => SORT_ASC])->all();
        return ArrayHelper::map($measures, 'unit_name', 'unit_name');
    }
       public static function getUnits() {
        $measures = static::find()->orderBy(['unit_of_measure_id' => SORT_ASC])->all();
        return ArrayHelper::map($measures, 'unit_of_measure_id', 'unit_name');
    }
}
