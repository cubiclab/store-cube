<?php

use yii\db\Migration;

class m060000_000001_init_dap_terms_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Delivery & Payment variants table
        $this->createTable('{{%dap_terms}}', [
            'id'            => $this->primaryKey(),
            'type'          => $this->string(8)->notNull(),
            'name'          => $this->string(64)->notNull(),
            'description'   => $this->text(),
            'price'         => $this->money(10,2),
            'discount'      => $this->smallInteger(3),
            'icon'          => $this->string(32),
            'status'        => $this->smallInteger(1)->notNull()->defaultValue(1),
            'order'         => $this->integer(),
        ], $tableOptions);
        $this->createIndex('order', '{{%dap_terms}}', ['type','order'], true);
        $this->createIndex('status', '{{%dap_terms}}', 'status', false);

        //Fill Default Values
        $this->execute($this->fillDefaultDelivery());
        $this->execute($this->fillDefaultPayment());
    }

    /** @return string SQL add default delivery service */
    private function fillDefaultDelivery()
    {
        return "INSERT INTO {{%dap_terms}} (id,`type`,`name`,description,price,discount,icon,status,`order`)
                VALUES (NULL, 'delivery','Доставка курьером', 'Товар доставляется курьером и вручается в руки.', '350.00', '', '', '1', '10')";
    }

    /** @return string SQL add default delivery service */
    private function fillDefaultPayment()
    {
        return "INSERT INTO {{%dap_terms}} (id,`type`,`name`,description,price,discount,icon,status,`order`)
                VALUES (NULL, 'payment','Наличными', 'Оплата производится курьеру', '', '', '','1', '10')";
    }

    public function safeDown()
    {
        $this->dropTable('{{%dap_terms}}');
    }

}
