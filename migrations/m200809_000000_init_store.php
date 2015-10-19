<?php

use yii\db\Schema;
use yii\db\Migration;

class m200809_000000_init_store extends Migration
{
    public function safeUp()
    {
        //Activate Store Cube
        $this->execute($this->activateStoreCube());
    }

    /** @return string SQL to activate store cube */
    private function activateStoreCube()
    {
        return "INSERT INTO {{%cubes}} (module_id, `name`, class, title, icon, settings, notice, `order`, status)
                                VALUES (NULL, 'store', 'cubiclab\\\\store\\\\StoreCube', 'Store', 'fa-shopping-cart', NULL, 0, 10, 1)";
    }

    public function safeDown()
    {

    }

}
