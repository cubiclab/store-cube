<?php

use yii\db\Schema;
use yii\db\Migration;

class m200809_000001_init_catalog_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Categories table
        $this->createTable('{{%categories}}', [
            'id'           => Schema::TYPE_PK,
            'parent'       => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'name'         => Schema::TYPE_STRING . '(64) NOT NULL',
            'description'  => Schema::TYPE_TEXT   . ' NULL DEFAULT NULL',
            'icon'         => Schema::TYPE_STRING . '(32) DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('parent', '{{%categories}}', 'parent', false);

        // Products table
        $this->createTable('{{%products}}', [
            'id'           => Schema::TYPE_PK,
            'name'         => Schema::TYPE_STRING . '(64) NOT NULL',
            'description'  => Schema::TYPE_TEXT   . ' NULL DEFAULT NULL'
        ], $tableOptions);

        // Category_Product table (Connection)
        $this->createTable('{{%category_product}}', [
            'id'           => Schema::TYPE_PK,
            'cat_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'prod_id'      => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('cat_id', '{{%category_product}}', 'cat_id', false);
        $this->createIndex('prod_id', '{{%category_product}}', 'prod_id', false);
        $this->addForeignKey('FK_category', '{{%category_product}}', 'cat_id', '{{%categories}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_product', '{{%category_product}}', 'prod_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');


        // Products images table
        $this->createTable('{{%products_images}}', [
            'id'           => Schema::TYPE_PK,
            'prod_id'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'image_url'    => Schema::TYPE_STRING  . '(128) NOT NULL'
        ], $tableOptions);
        $this->createIndex('prod_id', '{{%products_images}}', 'prod_id', false);
        $this->addForeignKey('FK_products_images', '{{%products_images}}', 'prod_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_category', '{{%category_product}}');
        $this->dropForeignKey('FK_product', '{{%category_product}}');
        $this->dropForeignKey('FK_products_images', '{{%products_images}}');

        $this->dropTable('{{%categories}}');
        $this->dropTable('{{%products}}');
        $this->dropTable('{{%category_product}}');
        $this->dropTable('{{%products_images}}');
    }

}
