<?php

use yii\db\Migration;

class m040000_000002_init_parameters_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Parameters table
        $this->createTable('{{%parameters}}', [
            'id'           => $this->primaryKey(),
            'name'         => $this->string(64)->notNull(),
            'description'  => $this->text(),
            'units'        => $this->string(64),
            'digit'        => $this->smallInteger(),
            'is_range'     => $this->string(1)->defaultValue('N'),
            'icon'         => $this->string(32),
            'status'       => $this->smallInteger(1)->notNull()->defaultValue(1),
            'order'        => $this->integer(),
        ], $tableOptions);
        $this->createIndex('name', '{{%parameters}}', 'name', true);
        $this->createIndex('order', '{{%parameters}}', 'order', true);
        $this->createIndex('status', '{{%parameters}}', 'status', false);

        // Parameters_range table
        $this->createTable('{{%parameters_range}}', [
            'id'           => $this->primaryKey(),
            'param_id'     => $this->integer()->notNull(),
            'name'         => $this->string(64)->notNull(),
            'icon'         => $this->string(32),
            'status'       => $this->smallInteger(1)->notNull()->defaultValue(1),
            'order'        => $this->integer(),
        ], $tableOptions);
        $this->createIndex('param_id', '{{%parameters_range}}', 'param_id', false);
        $this->createIndex('order', '{{%parameters_range}}', ['param_id','order'], true);
        $this->createIndex('status', '{{%parameters_range}}', 'status', false);
        $this->addForeignKey('FK_parameters_range', '{{%parameters_range}}', 'param_id', '{{%parameters}}', 'id', 'CASCADE', 'CASCADE');

        // Parameters_values table
        $this->createTable('{{%parameters_values}}', [
            'product_id'   => $this->integer()->notNull(),
            'param_id'     => $this->integer()->notNull(),
            'range_id'     => $this->integer()->notNull()->defaultValue(0),
            'param_value'  => $this->text(),
            'PRIMARY KEY (product_id, param_id, range_id)',
        ], $tableOptions);
        $this->createIndex('product_id', '{{%parameters_values}}', 'product_id', false);
        $this->createIndex('param_id', '{{%parameters_values}}', 'param_id', false);
        $this->addForeignKey('FK_parameters_values', '{{%parameters_values}}', 'param_id', '{{%parameters}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('FK_parameters_product', '{{%parameters_values}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_parameters_range', '{{%parameters_range}}');
        $this->dropForeignKey('FK_parameters_values', '{{%parameters_values}}');
        $this->dropForeignKey('FK_parameters_product', '{{%parameters_values}}');

        $this->dropTable('{{%parameters}}');
        $this->dropTable('{{%parameters_range}}');
        $this->dropTable('{{%parameters_values}}');
    }

}
