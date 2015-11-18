<?php

use yii\db\Schema;
use yii\db\Migration;

class m200809_000000_init_store extends Migration
{
    public function safeUp()
    {
        //Activate Store Cube
        $this->execute($this->activateStoreCube());

        $permissions = [
            //Categories
            'ACPCategoriesView' => 'Can view Categories',
            'ACPCategoriesCreate' => 'Can create Categories',
            'ACPCategoriesUpdate' => 'Can update Categories',
            'ACPCategoriesDelete' => 'Can delete Categories',
            //DAP
            'ACPDAPView' => 'Can view DAP',
            'ACPDAPCreate' => 'Can create DAP',
            'ACPDAPUpdate' => 'Can update DAP',
            'ACPDAPDelete' => 'Can delete DAP',
            //Orders
            'ACPOrdersView' => 'Can view Orders',
            'ACPOrdersCreate' => 'Can create Orders',
            'ACPOrdersUpdate' => 'Can update Orders',
            'ACPOrdersDelete' => 'Can delete Orders',
            //Parameters
            'ACPParametersView' => 'Can view Parameters',
            'ACPParametersCreate' => 'Can create Parameters',
            'ACPParametersUpdate' => 'Can update Parameters',
            'ACPParametersDelete' => 'Can delete Parameters',
            //Products
            'ACPProductsView' => 'Can view Products',
            'ACPProductsCreate' => 'Can create Products',
            'ACPProductsUpdate' => 'Can update Products',
            'ACPProductsDelete' => 'Can delete Products',
        ];

        foreach ($permissions as $permission => $description) {
            $permit = Yii::$app->authManager->createPermission($permission);
            $permit->description = $description;
            Yii::$app->authManager->add($permit);
        }

        //set permissions to roles
        $roles_permissions = [
            'RootAdmin' => [
                'ACPParametersCreate',
                'ACPParametersUpdate',
                'ACPParametersDelete',
            ],
            'Admin' => [
                'ACPCategoriesCreate',
                'ACPCategoriesUpdate',
                'ACPCategoriesDelete',
                'ACPDAPCreate',
                'ACPDAPUpdate',
                'ACPDAPDelete',
                'ACPOrdersCreate',
                'ACPOrdersUpdate',
                'ACPOrdersDelete',
                'ACPProductsCreate',
                'ACPProductsUpdate',
                'ACPProductsDelete',
            ],
            'Moderator' => [
                'ACPCategoriesView',
                'ACPDAPView',
                'ACPOrdersView',
                'ACPOrdersUpdate',
                'ACPParametersView',
                'ACPProductsView',
            ],
        ];
        foreach ($roles_permissions as $role => $permissions) {
            foreach ($permissions as $permit) {
                Yii::$app->authManager->addChild(Yii::$app->authManager->getRole($role), Yii::$app->authManager->getPermission($permit));
            }
        }
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
