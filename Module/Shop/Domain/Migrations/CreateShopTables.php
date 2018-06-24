<?php
namespace Module\Shop\Domain\Migrations;

use Module\Shop\Domain\Model\ArticleCategoryModel;
use Module\Shop\Domain\Model\ArticleModel;
use Module\Shop\Domain\Model\BrandModel;
use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;
use Module\Shop\Domain\Model\NavigationModel;
use Module\Shop\Domain\Model\OrderAddressModel;
use Module\Shop\Domain\Model\OrderGoodsModel;
use Module\Shop\Domain\Model\OrderModel;
use Module\Shop\Domain\Model\PaymentModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateShopTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::createTable(ArticleModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('cat_id')->int()->notNull();
            $table->set('title')->varchar(100)->notNull()->comment('文章名');
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('thumb')->varchar(200)->comment('缩略图');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('brief')->varchar(200)->comment('简介');
            $table->set('url')->varchar(200)->comment('链接');
            $table->set('file')->varchar(200)->comment('下载内容');
            $table->set('content')->text()->notNull()->comment('内容');
            $table->timestamps();
        });

        Schema::createTable(ArticleCategoryModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('文章分类名');
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
        });
        Schema::createTable(CategoryModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('分类名');
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('thumb')->varchar(200);
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
        });
        Schema::createTable(NavigationModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('type')->varchar(10)->notNull()->defaultVal('middle');
            $table->set('name')->varchar(100)->notNull();
            $table->set('url')->varchar(200);
            $table->set('target')->varchar(10);
            $table->set('visible')->notNull()->bool()->defaultVal(1);
            $table->set('position')->notNull()->tinyint(3)->defaultVal(99);
        });
        Schema::createTable(BrandModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('分类名');
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('logo')->varchar(200)->comment('LOGO');
            $table->set('url')->varchar(200)->comment('官网');
        });
        Schema::createTable(GoodsModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('cat_id')->int()->notNull();
            $table->set('brand_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull()->comment('商品名');
            $table->set('sign')->varchar(100)->notNull();
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('thumb')->varchar(200)->comment('缩略图');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('brief')->varchar(200)->comment('简介');
            $table->set('content')->text()->notNull()->comment('内容');
            $table->set('price')->decimal(8, 2)->defaultVal(0);
            $table->set('market_price')->decimal(8, 2)->defaultVal(0);
            $table->set('stock')->int()->defaultVal(1);
            $table->set('is_show')->bool()->defaultVal(1);
            $table->set('is_best')->bool()->defaultVal(0);
            $table->set('is_hot')->bool()->defaultVal(0);
            $table->set('is_new')->bool()->defaultVal(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(CartModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->set('number')->int()->defaultVal(1);
            $table->set('price')->decimal(8, 2);
        });
        Schema::createTable(OrderModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('status')->int()->notNull();
            $table->set('payment_id')->int()->notNull();
            $table->set('shipping_id')->int()->defaultVal(1);
            $table->timestamps();
        });
        Schema::createTable(OrderGoodsModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('order_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->set('number')->int()->defaultVal(1);
            $table->set('price')->decimal(8, 2);
        });
        Schema::createTable(OrderAddressModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('name')->varchar(30)->notNull();
            $table->set('region_id')->int()->notNull();
            $table->set('tel')->varchar(11)->notNull();
            $table->set('address')->varchar()->notNull();
        });
        Schema::createTable(PaymentModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('code')->varchar(30)->notNull();
            $table->set('icon')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('settings')->text();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(ArticleModel::tableName());
        Schema::dropTable(ArticleCategoryModel::tableName());
        Schema::dropTable(NavigationModel::tableName());
    }

}