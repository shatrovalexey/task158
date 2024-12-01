<?php

use yii\db\Migration;
use common\models\{LogModel, Url};

/**
 * Handles the creation of table `{{%url}}`.
 */
class m241130_044359_create_url_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Url::tableName(), [
			'id' => $this->primaryKey() // ID
            , 'href' => $this->string()->notNull()->unique() // url для проверки
			, 'frequency' => $this->integer()->unsigned()->notNull()->defaultValue(5) // частота проверки, минуты
			, 'repetitions' => $this->integer()->notNull()->defaultValue(0) // количество повторов в случае ошибки, -1=бесконечность
			, 'delay' => $this->integer()->unsigned()->notNull()->defaultValue(5), // задержка в минутах между переповторами
        ]);

		$this->createIndex('idx-href-repetitions', Url::tableName(), ['href', 'repetitions',]);

		$this->addCommentOnTable(Url::tableName(), 'ссылки на проверку');
		$this->addCommentOnColumn(Url::tableName(), 'id', 'ID');
		$this->addCommentOnColumn(Url::tableName(), 'href', 'url для проверки');
		$this->addCommentOnColumn(Url::tableName(), 'frequency', 'частота проверки, минуты');
		$this->addCommentOnColumn(Url::tableName(), 'repetitions', 'количество повторов в случае ошибки, -1=бесконечность');
		$this->addCommentOnColumn(Url::tableName(), 'delay', 'задержка в минутах между переповторами');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Url::tableName());
    }
}
