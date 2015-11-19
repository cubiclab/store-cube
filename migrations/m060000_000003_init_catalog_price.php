<?php

use yii\db\Migration;

class m060000_000003_init_catalog_price extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Price types
        $this->createTable('{{%price_types}}', [
            'id'            => $this->primaryKey(),
            'name'          => $this->string(64)->notNull(),
            'currency_code' => $this->string(3)->notNull(),
            'currency_symbol' => $this->string(10),
            'data'          => $this->string(255),
            'icon'          => $this->string(32),
            'status'        => $this->smallInteger(1)->notNull()->defaultValue(1),
            'order'         => $this->integer(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
            'created_by'    => $this->integer(),
            'updated_by'    => $this->integer(),
        ], $tableOptions);

        // Foreign Keys
        $this->addForeignKey('FK_price_types_currency_code', '{{%price_types}}', 'currency_code', '{{%nsi_currency}}', 'currency_code', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('FK_price_types_currency_symbol', '{{%price_types}}', ['currency_code','currency_symbol'], '{{%nsi_currency_symbol}}', ['currency_code','currency_symbol'], 'RESTRICT', 'CASCADE');

        // Price
        $this->createTable('{{%prices}}', [
            'product_id'    => $this->integer()->notNull(),
            'price_type_id' => $this->integer()->notNull(),
            'price'         => $this->money(10,2),
            'status'        => $this->smallInteger(1)->notNull()->defaultValue(1),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
            'created_by'    => $this->integer(),
            'updated_by'    => $this->integer(),
            'PRIMARY KEY (product_id, price_type_id)',
        ], $tableOptions);
        $this->createIndex('status', '{{%prices}}', 'status', false);

        // Foreign Keys
        $this->addForeignKey('FK_prices_types', '{{%prices}}', 'price_type_id', '{{%price_types}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('FK_prices_products', '{{%prices}}', 'product_id', '{{%products}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_price_types_currency_code', '{{%price_types}}');
        $this->dropForeignKey('FK_price_types_currency_symbol', '{{%price_types}}');
        $this->dropForeignKey('FK_prices_types', '{{%prices}}');
        $this->dropForeignKey('FK_prices_products', '{{%prices}}');

        $this->dropTable('{{%price_types}}');
        $this->dropTable('{{%prices}}');
    }

}
