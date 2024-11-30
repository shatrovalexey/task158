<?php

use yii\db\Migration;

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
        $this->createTable('{{%url}}', [
			'id' => $this->primaryKey() // ID
            , 'href' => $this->string()->notNull()->unique() // url для проверки
			, 'frequency' => $this->integer()->unsigned()->notNull()->defaultValue(5) // частота проверки, минуты
			, 'repetitions' => $this->integer()->notNull()->defaultValue(0) // количество повторов в случае ошибки, -1=бесконечность
			, 'delay' => $this->integer()->unsigned()->notNull()->defaultValue(5), // задержка в минутах между переповторами
        ]);

		$this->addCommentOnTable('{{%url}}', 'ссылки на проверку');

		$this->addCommentOnColumn('{{%url}}', 'id', 'ID');
		$this->addCommentOnColumn('{{%url}}', 'href', 'url для проверки');
		$this->addCommentOnColumn('{{%url}}', 'frequency', 'частота проверки, минуты');
		$this->addCommentOnColumn('{{%url}}', 'repetitions', 'количество повторов в случае ошибки, -1=бесконечность');
		$this->addCommentOnColumn('{{%url}}', 'delay', 'задержка в минутах между переповторами');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%url}}');
    }
}
