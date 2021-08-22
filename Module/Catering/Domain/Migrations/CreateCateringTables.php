<?php
namespace Module\Catering\Domain\Migrations;

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
use Module\Catering\Domain\Entities\RecipeEntity;
use Module\Catering\Domain\Entities\RecipeMaterialEntity;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCateringTables extends Migration {

    public function up() {
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
            $table->string('name');
            $table->uint('parent_id');
            $table->timestamps();
        })->append(GoodsEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('store_id');
            $table->string('name');
            $table->string('image')->default('');
            $table->string('recipe_id')->default('');
            $table->string('description')->default('');
            $table->timestamps();
        })->append(GoodsGalleryEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->uint('file_type');
            $table->string('file');
        })->append(OrderEntity::tableName(), function(Table $table) {
            $table->id();
            // TODO
        })->append(OrderGoodsEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('goods_id');
            $table->uint('amount');
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2);
        })->append(PurchaseOrderEntity::tableName(), function(Table $table) {
            $table->comment('采购单');
            $table->id();
            // TODO
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
        })->autoUp();
    }

}