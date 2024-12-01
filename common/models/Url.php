<?php

namespace common\models;

use yii\base\Model;
use yii\db\ActiveRecord;
use common\models\LogModel;

/**
* Настройки для обработки URL
*/
class Url extends ActiveRecord
{
    public $primaryKey = 'id';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%url}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',], 'integer',],
            [['href',], 'url', 'defaultScheme' => 'https',],
            [['href', 'frequency', 'repetitions', 'delay',], 'required',],
            [['delay',], 'integer', 'min' => 1,],
            [['frequency',], 'integer','min' => 0,],
            [['repetitions',], 'integer','min' => -1,],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID'
            , 'href' => 'URL'
            , 'frequency' => 'частота проверки, минуты'
            , 'repetitions' => 'количество повторов, -1=бесконечность'
            , 'delay' => 'задержка в минутах между повторами',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getLogs()
    {
        return $this->hasMany(LogModel::class, ['id' => 'url_id',]);
    }
}