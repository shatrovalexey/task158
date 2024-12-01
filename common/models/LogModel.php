<?php

namespace common\models;

use yii\base\Model;
use yii\db\{ActiveRecord, Expression};
use common\models\Url;
use yii\behaviors\TimestampBehavior;

/**
* Журнал запросов к URL
*/
class LogModel extends ActiveRecord
{
    /**
    * @const CODE_MIN - минимальное значение неошибочного HTTP-кода ответа
    */
    protected const CODE_MIN = 100;

    /**
    * @const CODE_MIN - максимальное значение неошибочного HTTP-кода ответа
    */
    protected const CODE_MAX = 399;

    public $id;
    public $href;
    public $repeated;
    public $repetitions;
    public $success;
    public $expired;
    public $url_id;
    public $log_id;

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'url_id', 'log_id', 'code', 'success', 'expired', 'repeated', 'repetitions',], 'integer',],
            [['body',], 'text',],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID записи журнала'
            , 'log_id' => 'ID записи журнала'
            , 'href' => 'URL'
            , 'url_id' => 'ID URL'
            , 'code' => 'HTTP-код'
            , 'body' => 'ответ'
            , 'created_at' => 'дата-время'
            , 'repeated' => 'запрошено'
            , 'repetitions' => 'лимит'
            , 'success' => 'успех'
            , 'expired' => 'исчерпано',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->hasOne(Url::class, ['id' => 'url_id',]);
    }

    /**
     * {@inheritdoc}
     */
    public function getlogs()
    {
        return $this->hasMany(static::class, ['id' => 'url_id',]);
    }

    /**
    * Сводные данные по запросам к URL
    */
    protected static function _getLatest()
    {
        return static::find()
            ->select([
                'u1.href'
                , 'u1.repetitions'
                , 'count(l1.id) AS repeated'
                , 'max(l1.id) as log_id'
                , 'max(l1.code BETWEEN :code_min AND :code_max) AS success'
                , '(u1.repetitions <> -1) OR (count(l1.id) >= u1.repetitions) AS expired',
            ])
            ->from('url AS u1')
            ->leftJoin('logs AS l1', 'l1.url_id = u1.id')
            ->groupBy(['u1.href', 'u1.repetitions',])
            ->addParams([
                ':code_min' => static::CODE_MIN
                , ':code_max' => static::CODE_MAX,
            ]);
    }

    /**
    * Добавить запись
    */
    public static function addItem(string $href, int $code, ?string $body = null)
    {
        $model_url = Url::find()->select(['id',])->where(['href' => $href,])->one();

        if (!$model_url) {
            return false;
        }

        $url_id = $model_url->id;

        $model_log = new static();
        $model_log->url_id = $url_id;
        $model_log->code = $code;
        $model_log->body = $body;

        return $model_log->save();
    }

    /**
    * URL, которые требуют запросов
    */
    public static function getAvailable()
    {
        return static::find()
            ->select([
                'u1.href'
                ,  'u1.delay'
                , 'count(l1.id) AS repeated',
            ])
            ->from('url AS u1')
            ->leftJoin('logs AS l1', 'u1.id = l1.url_id')
            ->groupBy(['u1.id', 'u1.repetitions',])
            ->having(new \yii\db\Expression('
    (repeated = 0) OR (
        (u1.repetitions NOT BETWEEN :inf AND repeated) AND
        (min(l1.code) NOT BETWEEN :code_min AND :code_max) AND
        (max(l1.created_at) < adddate(now(), INTERVAL - u1.delay DAY))
    )
            '))
            ->addParams([
                ':inf' => -1
                , ':code_min' => static::CODE_MIN
                , ':code_max' => static::CODE_MAX,
            ]);
    }

    /**
    * Совмещённые данные по запросам к URL
    */
    public static function getReport()
    {
        return static::find()
            ->select([
                'l1.created_at'
                , 't1.href'
                , 'l1.code'
                , 'l1.body'
                , 't1.repeated'
                , 't1.repetitions'
                , 't1.success'
                , 't1.expired',
            ])
            ->from(['t1' => static::_getLatest(),])
            ->leftJoin('logs AS l1', 't1.log_id = l1.id')
            ->orderBy(['l1.created_at' => SORT_DESC,]);
    }
}