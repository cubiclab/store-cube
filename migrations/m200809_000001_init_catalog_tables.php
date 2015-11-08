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
            'id'           => $this->primaryKey(),
            'parent'       => $this->integer()->defaultValue(0),
            'name'         => $this->string(64)->notNull(),
            'description'  => $this->text(),
            'icon'         => $this->string(32),
            'status'       => $this->smallInteger(1)->notNull()->defaultValue(0),
            'order'        => $this->integer(),
        ], $tableOptions);
        $this->createIndex('parent', '{{%categories}}', 'parent', false);
		
        // Products table
        $this->createTable('{{%products}}', [
            'id'           => Schema::TYPE_PK,
            'article'      => Schema::TYPE_STRING . '(64) NOT NULL',
            'name'         => Schema::TYPE_STRING . '(64) NOT NULL',
            'short_desc'   => Schema::TYPE_TEXT    . ' NULL DEFAULT NULL',
            'description'  => Schema::TYPE_TEXT   . ' NULL DEFAULT NULL',
			'price'		   => Schema::TYPE_DECIMAL   . '(10, 2) NULL DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('article', '{{%products}}', 'article', true);

        // Category_Product table (Connection)
        $this->createTable('{{%category_product}}', [
            'cat_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'prod_id'      => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);
        $this->primaryKey('cat_id', 'prod_id');
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
