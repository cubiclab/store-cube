<?php

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
            'slug'         => $this->string(128),
            'status'       => $this->smallInteger(1)->notNull()->defaultValue(1),
            'order'        => $this->integer(),
        ], $tableOptions);
        $this->createIndex('parent', '{{%categories}}', 'parent', false);
        $this->createIndex('slug', '{{%categories}}', 'slug', true);
        $this->createIndex('order', '{{%categories}}', 'order', true);
        $this->createIndex('status', '{{%categories}}', 'status', false);

        // Products table
        $this->createTable('{{%products}}', [
            'id'           => $this->primaryKey(),
            'article'      => $this->string(64)->notNull(),
            'name'         => $this->string(64)->notNull(),
            'short_desc'   => $this->text(),
            'description'  => $this->text(),
            'slug'         => $this->string(128),
            'status'       => $this->smallInteger(1)->notNull()->defaultValue(1),
            'order'        => $this->integer(),
        ], $tableOptions);
        $this->createIndex('article', '{{%products}}', 'article', true);
        $this->createIndex('slug', '{{%products}}', 'slug', true);
        $this->createIndex('order', '{{%products}}', 'order', true);
        $this->createIndex('status', '{{%products}}', 'status', false);

        // Category_Product table (Connection)
        $this->createTable('{{%category_product}}', [
            'category_id'   => $this->integer()->notNull(),
            'product_id'    => $this->integer()->notNull(),
            'PRIMARY KEY (category_id, product_id)',
        ], $tableOptions);
        $this->addForeignKey('FK_category', '{{%category_product}}', 'category_id', '{{%categories}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_product', '{{%category_product}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');

        // Products images table
        $this->createTable('{{%products_images}}', [
            'id'            => $this->primaryKey(),
            'product_id'    => $this->integer()->notNull(),
            'image_url'     => $this->string(128)->notNull(),
        ], $tableOptions);
        $this->createIndex('product_id', '{{%products_images}}', 'product_id', false);
        $this->addForeignKey('FK_products_images', '{{%products_images}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');
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
