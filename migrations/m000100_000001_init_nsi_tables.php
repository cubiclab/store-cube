<?php

use yii\db\Schema;
use yii\db\Migration;

class m000100_000001_init_nsi_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // NSI Currency table (ОКВ)
        $this->createTable('{{%nsi_currency}}', [
            'currency_code'     => $this->string(3)->notNull(),
            'currency_numcode'  => $this->smallInteger(3)->notNull(),
            'currency_name'     => $this->string(),
            'currency_country'  => $this->text(),
            'PRIMARY KEY (currency_code, currency_numcode)',
        ], $tableOptions);

        $this->createTable('{{%nsi_currency_symbol}}', [
            'currency_code'     => $this->string(3)->notNull(),
            'currency_symbol'   => $this->string(10),
            'PRIMARY KEY (currency_code)',
        ], $tableOptions);

        $this->addForeignKey('FK_currency_symbol', '{{%nsi_currency_symbol}}', 'currency_code', '{{%nsi_currency}}', 'currency_code', 'CASCADE', 'CASCADE');

    }

    /** @return string SQL to activate store cube */
    private function fillCurrencyValues()
    {
        return "INSERT INTO {{%nsi_currency}} (currency_code, currency_numcode, currency_name, currency_country, currency_symbol)
                VALUES ('AUD', '036', 'Австралийский доллар', 'Австралия'),
('BYR', '974', 'Белорусский рубль', 'Республика Беларусь),
('VND', '704', 'Вьетнамский донг', 'Народная Республика Вьетнам),
('GEL', '981', 'Грузинский лари', 'Республика Грузия),
('USD', '840', 'Доллар США', 'Соединенные Штаты Америки (США)'),
('EUR', '978', 'ЕВРО', 'Европейский Союз'),
('KZT', '398', 'Казахстанский тенге', 'Республика Казахстан'),
('CNY', '156', 'Китайский юань Жэньминьби', 'Китайская Народная Республика (КНР)'),
('MDL', '498', 'Молдавский лей', 'Республика Молдова'),
('RUB', '643', 'Российский рубль', 'Российская Федерация'),
('SGD', '702', 'Сингапурский доллар', 'Республика Сингапур'),
('GBP', '826', 'Фунт стерлингов', 'Соединенное Королевство Великобритании и Северной Ирландии'),
('JPY', '392', 'Японская йена', 'Япония')";
    }

    public function safeDown()
    {

    }

}
