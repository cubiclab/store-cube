<?php
/**
 * Created by PhpStorm.
 * User: pt1c
 * Date: 18.11.2015
 * Time: 8:10
 */

use yii\db\Schema;
use yii\db\Migration;

class m200809_000004_init_orders_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Delivery & Payment variants table
        $this->createTable('{{%orders}}', [
            'id'            => $this->primaryKey(),
            'dap_id'        => $this->integer()->notNull(),
            'status'        => $this->smallInteger(1)->notNull(),
            'name'          => $this->string(64)->notNull(),
            'address'       => $this->string(255)->notNull(),
            'phone'         => $this->string(64)->notNull(),
            'email'         => $this->string(128)->notNull(),
            'comment'       => $this->string(1024)->notNull(),
            'remark'        => $this->string(1024)->notNull(),
            'access_token'  => $this->string(32)->notNull(),
            'total_price'   => $this->money(10,2),
            'ip'            => $this->string(16)->notNull(),
            'created_at'    => $this->integer()->defaultValue(NULL),
            'updated_at'    => $this->integer()->defaultValue(NULL),
            'created_by'    => $this->integer()->defaultValue(NULL),
            'updated_by'    => $this->integer()->defaultValue(NULL),
        ], $tableOptions);

        // Foreign Keys
        $this->addForeignKey('FK_orders_dap', '{{%orders}}', 'dap_id', '{{%dap_terms}}', 'id', 'RESTRICT', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_orders_dap', '{{%orders}}');

        $this->dropTable('{{%orders}}');
    }

}
