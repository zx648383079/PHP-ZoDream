<?php
namespace Module\Catering\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Catering\Domain\Entities\AddressEntity;
use Module\Catering\Domain\Entities\CartEntity;
use Module\Catering\Domain\Entities\CategoryEntity;
use Module\Catering\Domain\Entities\GoodsEntity;
use Module\Catering\Domain\Entities\GoodsGalleryEntity;
use Module\Catering\Domain\Entities\MaterialEntity;
use Module\Catering\Domain\Entities\MaterialPriceEntity;
use Module\Catering\Domain\Entities\MaterialUnitEntity;
use Module\Catering\Domain\Entities\OrderEntity;
use Module\Catering\Domain\Entities\OrderGoodsEntity;
use Module\Catering\Domain\Entities\PurchaseOrderEntity;
use Module\Catering\Domain\Entities\PurchaseOrderGoodsEntity;
use Module\Catering\Domain\Entities\RecipeEntity;
use Module\Catering\Domain\Entities\RecipeMaterialEntity;
use Module\Catering\Domain\Entities\StoreEntity;
use Module\Catering\Domain\Entities\StoreFloorEntity;
use Module\Catering\Domain\Entities\StoreMetaEntity;
use Module\Catering\Domain\Entities\StorePatronEntity;
use Module\Catering\Domain\Entities\StorePatronGroupEntity;
use Module\Catering\Domain\Entities\StorePlaceEntity;
use Module\Catering\Domain\Entities\StoreRoleEntity;
use Module\Catering\Domain\Entities\StoreStaffEntity;
use Module\Catering\Domain\Entities\StoreStockEntity;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCateringTables extends Migration {

    public function up(): void {
        $this->append(AddressEntity::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->uint('region_id');
            $table->uint('user_id');
            $table->char('tel', 11);
            $table->string('address');
            $table->string('longitude', 50)->default('')->comment('经度');
            $table->string('latitude', 50)->default('')->comment('纬度');
            $table->timestamps();
        })->append(CartEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('type', 1)->default(0)->comment('订单类型');
            $table->uint('user_id');
            $table->uint('store_id');
            $table->uint('goods_id');
            $table->uint('amount');
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->timestamps();
        })->append(CategoryEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('store_id');
            $table->uint('type', 1)->default(0);
            $table->string('name');
            $table->uint('parent_id');
        })->append(GoodsEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('store_id');
            $table->uint('cat_id');
            $table->string('name');
            $table->string('image')->default('');
            $table->uint('recipe_id')->default(0);
            $table->string('description')->default('');
            $table->timestamps();
        })->append(GoodsGalleryEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->string('thumb')->default('');
            $table->uint('file_type', 1)->default(0);
            $table->string('file');
        })->append(OrderEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('store_id');
            $table->uint('waiter_id')->default(0);
            $table->uint('address_type', 1)->default(0);
            $table->string('address_name', 20)->default('');
            $table->string('address_tel', 20)->default('');
            $table->string('address')->default('');
            $table->uint('payment_id')->default(0);
            $table->string('payment_name', 30)->default(0);
            $table->decimal('goods_amount', 8, 2)->default(0);
            $table->decimal('order_amount', 8, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('shipping_fee', 8, 2)->default(0);
            $table->decimal('pay_fee', 8, 2)->default(0);
            $table->uint('status', 1)->default(0);
            $table->string('remark')->default('');
            $table->string('reserve_at')->default('')->comment('预约时间');
            $table->timestamp('pay_at')->comment('支付时间');
            $table->timestamp('shipping_at')->comment('发货时间');
            $table->timestamp('receive_at')->comment('签收时间');
            $table->timestamp('finish_at')->comment('完成时间');
            $table->timestamps();
        })->append(OrderGoodsEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('goods_id');
            $table->uint('amount');
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(PurchaseOrderEntity::tableName(), function(Table $table) {
            $table->comment('采购单');
            $table->id();
            $table->uint('store_id');
            $table->uint('user_id');
            $table->uint('status', 1)->default(0);
            $table->decimal('price', 8, 2);
            $table->string('remark')->default('');
            $table->uint('execute_id')->default(0)->comment('采购人');
            $table->uint('check_id')->default(0)->comment('验收人');
            $table->timestamp('execute_at');
            $table->timestamp('check_at');
            $table->timestamps();
        })->append(PurchaseOrderGoodsEntity::tableName(), function(Table $table) {
            $table->comment('采购单详情');
            $table->id();
            $table->uint('order_id');
            $table->uint('material_id');
            $table->decimal('amount', 8, 2);
            $table->uint('unit', 1);
            $table->decimal('price', 8, 2);
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(MaterialEntity::tableName(), function(Table $table) {
            $table->comment('原材料');
            $table->id();
            $table->string('name');
            $table->string('image')->default('');
            $table->string('description')->default('');
        })->append(MaterialPriceEntity::tableName(), function(Table $table) {
            $table->comment('原材料参考价格');
            $table->id();
            $table->string('material_id');
            $table->decimal('amount', 8, 2);
            $table->uint('unit', 1);
            $table->decimal('price', 8, 2);
            $table->timestamps();
        })->append(MaterialUnitEntity::tableName(), function(Table $table) {
            $table->comment('原材料单位换算');
            $table->id();
            $table->string('material_id');
            $table->decimal('from_amount', 8, 2);
            $table->uint('from_unit', 1);
            $table->decimal('to_amount', 8, 2);
            $table->uint('to_unit', 1);
        })->append(RecipeEntity::tableName(), function(Table $table) {
            $table->comment('食谱，库存组成商品的配方');
            $table->id();
            $table->uint('cat_id');
            $table->uint('store_id')->default(0);
            $table->uint('user_id');
            $table->string('name');
            $table->string('image')->default('');
            $table->string('description')->default('');
            $table->string('remark')->default('');
            $table->timestamps();
        })->append(RecipeMaterialEntity::tableName(), function(Table $table) {
            $table->comment('食谱的配方');
            $table->id();
            $table->uint('recipe_id');
            $table->uint('material_id');
            $table->decimal('amount', 8, 2);
            $table->uint('unit', 1);
        })->append(StoreEntity::tableName(), function(Table $table) {
            $table->comment('店铺');
            $table->id();
            $table->string('name', 100);
            $table->uint('user_id');
            $table->string('logo')->default('');
            $table->string('description')->default('');
            $table->string('address')->default('');
            $table->bool('open_status')->default(0);
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(StoreMetaEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('item_id');
            $table->string('name', 100);
            $table->text('content');
        })->append(StoreStaffEntity::tableName(), function(Table $table) {
            $table->comment('店铺员工');
            $table->id();
            $table->uint('store_id');
            $table->uint('user_id');
            $table->uint('role_id')->default(0);
            $table->timestamps();
        })->append(StoreRoleEntity::tableName(), function(Table $table) {
            $table->comment('店铺员工权限');
            $table->id();
            $table->uint('store_id');
            $table->string('name', 20);
            $table->string('description')->default('');
            $table->string('action')->default('');
            $table->timestamps();
        })->append(StoreStockEntity::tableName(), function(Table $table) {
            $table->comment('店铺库存');
            $table->id();
            $table->uint('store_id');
            $table->uint('cat_id');
            $table->uint('material_id');
            $table->decimal('amount', 8, 2);
            $table->uint('unit', 1);
            $table->timestamps();
        })->append(StorePatronEntity::tableName(), function(Table $table) {
            $table->comment('店铺顾客');
            $table->id();
            $table->uint('store_id');
            $table->uint('user_id');
            $table->uint('group_id');
            $table->uint('amount')->default(0)->comment('光顾次数');
            $table->string('name', 20)->default('');
            $table->string('remark')->default('');
            $table->timestamps();
        })->append(StorePatronGroupEntity::tableName(), function(Table $table) {
            $table->comment('店铺顾客分组');
            $table->id();
            $table->uint('store_id');
            $table->string('name', 20);
            $table->string('remark')->default('');
            $table->uint('discount', 2)->default(100);
        })->append(StoreFloorEntity::tableName(), function(Table $table) {
            $table->comment('店铺房间');
            $table->id();
            $table->uint('store_id');
            $table->string('name', 20);
            $table->text('map')->nullable();
        })->append(StorePlaceEntity::tableName(), function(Table $table) {
            $table->comment('店铺房间');
            $table->id();
            $table->uint('store_id');
            $table->uint('floor_id');
            $table->string('name', 20);
            $table->uint('user_id')->default(0);
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'catering_manage' => '餐饮管理'
        ]);
    }

}