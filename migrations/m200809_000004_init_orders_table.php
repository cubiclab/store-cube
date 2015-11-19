<?php

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
            'delivery_id'   => $this->integer()->notNull(),
            'payment_id'    => $this->integer()->notNull(),
            'status'        => $this->smallInteger(1)->notNull()->defaultValue(0),
            'name'          => $this->string(64)->notNull(),
            'address'       => $this->string(255)->notNull(),
            'phone'         => $this->string(64)->notNull(),
            'email'         => $this->string(128)->notNull(),
            'comment'       => $this->string(1024)->notNull(),
            'access_token'  => $this->string(32)->notNull(),
            'ip'            => $this->string(16)->notNull(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
            'created_by'    => $this->integer(),
            'updated_by'    => $this->integer(),
        ], $tableOptions);
        $this->createIndex('access_token', '{{%orders}}', 'access_token', true);

        // Foreign Keys
        $this->addForeignKey('FK_orders_delivery', '{{%orders}}', 'delivery_id', '{{%dap_terms}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('FK_orders_payment', '{{%orders}}', 'payment_id', '{{%dap_terms}}', 'id', 'RESTRICT', 'CASCADE');

        // Order Products
        $this->createTable('{{%orders_products}}', [
            'order_id'      => $this->integer()->notNull(),
            'product_id'    => $this->integer()->notNull(),
            'quantity'      => $this->integer()->notNull(),
            'options'       => $this->string(255),
            'price'         => $this->money(10,2),
            'discount'      => $this->money(10,2),
            'PRIMARY KEY (order_id, product_id)',
        ], $tableOptions);

        // Foreign Keys
        $this->addForeignKey('FK_orders_orders', '{{%orders_products}}', 'order_id', '{{%orders}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('FK_orders_products', '{{%orders_products}}', 'product_id', '{{%products}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_orders_delivery', '{{%orders}}');
        $this->dropForeignKey('FK_orders_payment', '{{%orders}}');
        $this->dropForeignKey('FK_orders_orders', '{{%orders_products}}');
        $this->dropForeignKey('FK_orders_products', '{{%orders_products}}');

        $this->dropTable('{{%orders}}');
        $this->dropTable('{{%orders_products}}');
    }

}
