<?php

use yii\db\Schema;
use yii\db\Migration;

class m200809_000002_init_parameters_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Parameters table
        $this->createTable('{{%parameters}}', [
            'id'           => Schema::TYPE_PK,
            'name'         => Schema::TYPE_STRING . '(64) NOT NULL',
            'description'  => Schema::TYPE_TEXT   . ' NULL DEFAULT NULL',
            'units'        => Schema::TYPE_STRING . '(64) DEFAULT NULL',
            'digit'        => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'icon'         => Schema::TYPE_STRING . '(32) DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('name', '{{%parameters}}', 'name', true);

        // Parameters_range table
        $this->createTable('{{%parameters_range}}', [
            'id'           => Schema::TYPE_PK,
            'param_id'     => Schema::TYPE_INTEGER,
            'name'         => Schema::TYPE_STRING . '(64) NOT NULL',
            'icon'         => Schema::TYPE_STRING . '(32) DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('param_id', '{{%parameters_range}}', 'param_id', false);
        $this->addForeignKey('FK_parameters_range', '{{%parameters_range}}', 'param_id', '{{%parameters}}', 'id', 'CASCADE', 'CASCADE');

        // Parameters_values table
        $this->createTable('{{%parameters_values}}', [
            'id'           => Schema::TYPE_PK,
            'product_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'param_id'     => Schema::TYPE_INTEGER . ' NOT NULL',
            'param_value'  => Schema::TYPE_TEXT . ' NOT NULL',
        ], $tableOptions);
        $this->createIndex('product_id', '{{%parameters_values}}', 'product_id', false);
        $this->createIndex('param_id', '{{%parameters_values}}', 'param_id', false);
        $this->addForeignKey('FK_parameters_values', '{{%parameters_values}}', 'param_id', '{{%parameters}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_parameters_product', '{{%parameters_values}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_parameters_range', '{{%parameters_range}}');
        $this->dropForeignKey('FK_parameters_values', '{{%parameters_values}}');

        $this->dropTable('{{%parameters}}');
        $this->dropTable('{{%parameters_range}}');
        $this->dropTable('{{%parameters_values}}');
    }

}
