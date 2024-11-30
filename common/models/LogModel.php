<?php

namespace common\models;

use yii\base\Model;
use yii\db\ActiveRecord;
use common\models\Url;
use yii\behaviors\TimestampBehavior;

class LogModel extends ActiveRecord
{
	public $primaryKey = 'id';
	public $href;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%logs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at',],
                ],
            ],
        ];
    }

    public function rules()
    {
        return [
			[['id', 'url_id', 'code',], 'integer',],
            [['body',], 'text',],
        ];
    }

	public function attributeLabels()
	{
		return [
			'id' => 'ID'
			, 'href' => 'URL'
			, 'url_id' => 'ID URL'
			, 'code' => 'HTTP-код'
			, 'body' => 'ответ'
			, 'created_at' => 'дата-время',
		];
	}

	public function getUrl()
	{
		return $this->hasOne(Url::class, ['id' => 'url_id']);
	}

	public function getlogs()
	{
		return $this->hasMany(static::class, ['id' => 'url_id',]);
	}

	protected static function _getLatest()
	{
		return static::find()
			->select(['u.href', 'max(l.id) as log_id',])
			->from('logs l')
			->innerJoinWith('url u')
			->groupBy('u.href');
	}

	public static function getReport()
	{
		return static::find()
			->select(['t1.href', 'l.code', 'l.body', 'l.created_at',])
			->from(['t1' => static::_getLatest(),])
			->innerJoin('logs l', 't1.log_id = l.id')
			->orderBy(['l.created_at' => SORT_DESC,]);
	}
}