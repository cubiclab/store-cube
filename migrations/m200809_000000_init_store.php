<?php

use yii\db\Schema;
use yii\db\Migration;

class m200809_121516_init_parameters_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Modules table
        $this->createTable('{{%cubes}}', [
            'module_id'     => Schema::TYPE_PK,
            'name'          => Schema::TYPE_STRING . '(64) NOT NULL',
            'class'         => Schema::TYPE_STRING . '(128) NOT NULL',
            'title'         => Schema::TYPE_STRING . '(128) NOT NULL',
            'icon'          => Schema::TYPE_STRING . '(32) NOT NULL',
            'settings'      => Schema::TYPE_TEXT . ' NULL DEFAULT NULL',
            'notice'        => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'order'         => Schema::TYPE_INTEGER,
            'status'        => Schema::TYPE_BOOLEAN . " DEFAULT '0'"
        ], $tableOptions);

        // Indexes
        $this->createIndex('name', '{{%cubes}}', 'name', true);
        $this->createIndex('status', '{{%cubes}}', 'status', true);
        $this->createIndex('order', '{{%cubes}}', 'order', true);


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
        $this->createIndex('param_id', '{{%parameters_range}}', 'param_id', true);


        // Parameters_values table
        $this->createTable('{{%parameters_values}}', [
            'id'           => Schema::TYPE_PK,
            'product_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'param_id'     => Schema::TYPE_INTEGER . ' NOT NULL',
            'param_value'  => Schema::TYPE_TEXT . ' NOT NULL',
        ], $tableOptions);
        $this->createIndex('product_id', '{{%parameters_values}}', 'product_id', true);
        $this->createIndex('param_id', '{{%parameters_values}}', 'param_id', true);




        //Activate Store Cube
        $this->execute($this->activateStoreCube());
    }

    /** @return string SQL to activate store cube */
    private function activateStoreCube()
    {
        return "INSERT INTO {{%cubes}} (module_id, `name`, class, title, icon, settings, notice, `order`, status)
                                VALUES (NULL, 'store', 'cubiclab\\store\\StoreCube', 'Store', 'fa-shopping-cart', NULL, 0, 1, 1)";
    }

    public function safeDown()
    {
        $this->dropTable('{{%cubes}}');
    }

}
