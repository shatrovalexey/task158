<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m241130_065733_init_rbac
 */
class m241130_065733_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$auth = Yii::$app->authManager;

		$createUrl = $auth->createPermission('createUrl');
		$createUrl->description = 'create URL';
		$auth->add($createUrl);

		$updateUrl = $auth->createPermission('updateUrl');
		$updateUrl->description = 'update URL';
		$auth->add($updateUrl);

		$user = $auth->createRole('user');
		$auth->add($user);
		$auth->addChild($user, $createUrl);

		$admin = $auth->createRole('admin');
		$auth->add($admin);
		$auth->addChild($admin, $updateUrl);
		$auth->addChild($admin, $user);

		$auth->assign($user, User::ROLE_USER);
		$auth->assign($admin, User::ROLE_ADMIN);

		$this->addColumn('{{%user}}', 'role', $this->integer());
		$this->addCommentOnColumn('{{%url}}', 'role', 'роль');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241130_065733_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
