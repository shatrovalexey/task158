<?php

namespace common\models;

use yii\base\Model;
use yii\db\ActiveRecord;
use common\models\LogModel;

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

	public function getLogs()
	{
		return $this->hasMany(LogModel::class, ['id' => 'url_id',]);
	}
}