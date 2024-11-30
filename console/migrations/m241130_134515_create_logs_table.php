<?php

use yii\db\Migration;
use common\models\{LogModel, Url};

/**
 * Handles the creation of table `{{%logs}}`.
 */
class m241130_134515_create_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(LogModel::tableName(), [
            'id' => $this->primaryKey()->comment('ID')
			, 'url_id' => $this->integer()->notNull()->comment('ID url')
			, 'code' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('HTTP-код')
			, 'body' => $this->text()->comment('ответ')
			, 'created_at' => $this->timestamp()->notNull()->comment('дата-время запроса'),
        ]);

		$this->createIndex('idx-created_at', LogModel::tableName(), 'created_at');
		$this->addCommentOnTable(LogModel::tableName(), 'журнал');
		$this->addForeignKey('fk-log-url', LogModel::tableName(), 'url_id', Url::tableName(), 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(LogModel::tableName());
    }
}
