<?php
namespace Module\Shop\Domain\Migrations;

use Module\Shop\Domain\Model\Activity\ActivityModel;
use Module\Shop\Domain\Model\AddressModel;
use Module\Shop\Domain\Model\Advertisement\AdModel;
use Module\Shop\Domain\Model\Advertisement\AdPositionModel;
use Module\Shop\Domain\Model\ArticleCategoryModel;
use Module\Shop\Domain\Model\ArticleModel;
use Module\Shop\Domain\Model\AttributeGroupModel;
use Module\Shop\Domain\Model\AttributeModel;
use Module\Shop\Domain\Model\BrandModel;
use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\CollectModel;
use Module\Shop\Domain\Model\CommentImageModel;
use Module\Shop\Domain\Model\CommentModel;
use Module\Shop\Domain\Model\CouponLogModel;
use Module\Shop\Domain\Model\CouponModel;
use Module\Shop\Domain\Model\GoodsAttributeModel;
use Module\Shop\Domain\Model\GoodsGalleryModel;
use Module\Shop\Domain\Model\GoodsIssue;
use Module\Shop\Domain\Model\GoodsModel;
use Module\Shop\Domain\Model\InvoiceModel;
use Module\Shop\Domain\Model\InvoiceTitleModel;
use Module\Shop\Domain\Model\NavigationModel;
use Module\Shop\Domain\Model\OrderActivityModel;
use Module\Shop\Domain\Model\OrderAddressModel;
use Module\Shop\Domain\Model\OrderCouponModel;
use Module\Shop\Domain\Model\OrderGoodsModel;
use Module\Shop\Domain\Model\OrderLogModel;
use Module\Shop\Domain\Model\OrderModel;
use Module\Shop\Domain\Model\OrderRefundModel;
use Module\Shop\Domain\Model\PaymentModel;
use Module\Shop\Domain\Model\ProductModel;
use Module\Shop\Domain\Model\RegionModel;
use Module\Shop\Domain\Model\ShippingGroupModel;
use Module\Shop\Domain\Model\ShippingModel;
use Module\Shop\Domain\Model\ShippingRegionModel;
use Module\Shop\Domain\Model\WarehouseGoodsModel;
use Module\Shop\Domain\Model\WarehouseModel;
use Module\Shop\Domain\Model\WarehouseRegionModel;
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
        $this->createAd();

        $this->createArticle();
        Schema::createTable(AddressModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('region_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('tel')->char(11)->notNull();
            $table->set('address')->varchar()->notNull();
            $table->timestamps();
        });
        Schema::createTable(CouponModel::tableName(), function(Table $table) {
            $table->setComment('优惠券');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('thumb')->varchar()->defaultVal('');
            $table->set('type')->tinyint(2)->defaultVal(0)->comment('优惠类型');
            $table->set('rule')->tinyint(2)->defaultVal(0)->comment('使用的商品');
            $table->set('rule_value')->tinyint(2)->defaultVal(0)->comment('使用的商品');
            $table->set('min_money')->decimal(8, 2)->defaultVal(0)->comment('满多少可用');
            $table->set('money')->decimal(8, 2)->defaultVal(0)->comment('几折或优惠金额');
            $table->set('send_type')->int()->defaultVal(0)->comment('发放类型');
            $table->set('send_value')->int()->defaultVal(0)->comment('发放条件或数量');
            $table->set('every_amount')->int()->defaultVal(0)->comment('每个用户能领取数量');
            $table->timestamps();
        });
        Schema::createTable(CouponLogModel::tableName(), function(Table $table) {
            $table->setComment('优惠券记录');
            $table->set('id')->pk()->ai();
            $table->set('coupon_id')->int()->notNull();
            $table->set('serial_number')->varchar(30)->defaultVal('');
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('order_id')->int()->defaultVal(0);
            $table->timestamp('used_at');
            $table->timestamps();
        });
        Schema::createTable(InvoiceModel::tableName(), function(Table $table) {
            $table->setComment('开票记录');
            $table->set('id')->pk()->ai();
            $table->set('title_type')->tinyint(1)->defaultVal(0)->comment('发票抬头类型');
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('发票类型');
            $table->set('title')->varchar(100)->notNull()->comment('抬头');
            $table->set('tax_no')->varchar(20)->notNull()->comment('税务登记号');
            $table->set('tel')->char(11)->notNull()->comment('注册场所电话');
            $table->set('bank')->varchar(100)->notNull()->comment('开户银行');
            $table->set('account')->varchar(60)->notNull()->comment('基本开户账号');
            $table->set('address')->varchar()->notNull()->comment('注册场所地址');
            $table->set('user_id')->int()->notNull();
            $table->set('money')->decimal(10, 2)->defaultVal(0)->comment('开票金额');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('开票状态');
            $table->timestamps();
        });
        Schema::createTable(InvoiceTitleModel::tableName(), function(Table $table) {
            $table->setComment('用户发票抬头');
            $table->set('id')->pk()->ai();
            $table->set('title_type')->tinyint(1)->defaultVal(0)->comment('发票抬头类型');
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('发票类型');
            $table->set('title')->varchar(100)->notNull()->comment('抬头');
            $table->set('tax_no')->varchar(20)->notNull()->comment('税务登记号');
            $table->set('tel')->char(11)->notNull()->comment('注册场所电话');
            $table->set('bank')->varchar(100)->notNull()->comment('开户银行');
            $table->set('account')->varchar(60)->notNull()->comment('基本开户账号');
            $table->set('address')->varchar()->notNull()->comment('注册场所地址');
            $table->set('user_id')->int()->notNull();
            $table->timestamps();
        });
        $this->createAttribute();

        $this->createComment();


        Schema::createTable(NavigationModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('type')->varchar(10)->notNull()->defaultVal('middle');
            $table->set('name')->varchar(100)->notNull();
            $table->set('url')->varchar(200);
            $table->set('target')->varchar(10);
            $table->set('visible')->notNull()->bool()->defaultVal(1);
            $table->set('position')->notNull()->tinyint(3)->defaultVal(99);
        });

        $this->createGoods();
        $this->createOrder();
        Schema::createTable(PaymentModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('code')->varchar(30)->notNull();
            $table->set('icon')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('settings')->text();
        });
        Schema::createTable(RegionModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('full_name')->varchar(100)->defaultVal('');
        });
        $this->createShipping();
        $this->createWarehouse();

        $this->createActivity();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(ArticleModel::tableName());
        Schema::dropTable(ArticleCategoryModel::tableName());
        Schema::dropTable(CollectModel::tableName());
        Schema::dropTable(GoodsModel::tableName());
        Schema::dropTable(CartModel::tableName());
        Schema::dropTable(NavigationModel::tableName());
    }


    /**
     * 创建订单
     */
    public function createOrder(): void {
        Schema::createTable(OrderModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('series_number')->varchar(100)->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('status')->int()->defaultVal(0);
            $table->set('payment_id')->int()->defaultVal(0);
            $table->set('payment_name')->varchar(30)->defaultVal(0);
            $table->set('shipping_id')->int()->defaultVal(0);
            $table->set('invoice_id')->int()->defaultVal(0)->comment('发票');
            $table->set('shipping_name')->varchar(30)->defaultVal(0);
            $table->set('goods_amount')->decimal(8, 2)->defaultVal(0);
            $table->set('order_amount')->decimal(8, 2)->defaultVal(0);
            $table->set('discount')->decimal(8, 2)->defaultVal(0);
            $table->set('shipping_fee')->decimal(8, 2)->defaultVal(0);
            $table->set('pay_fee')->decimal(8, 2)->defaultVal(0);
            $table->timestamps();
        });
        Schema::createTable(OrderGoodsModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('order_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull()->comment('商品名');
            $table->set('series_number')->varchar(100)->notNull();
            $table->set('thumb')->varchar(200)->comment('缩略图');
            $table->set('number')->int()->defaultVal(1);
            $table->set('price')->decimal(8, 2);
            $table->set('refund_id')->int()->defaultVal(0);
            $table->set('status')->int()->defaultVal(0);
            $table->set('after_sale_status')->int()->defaultVal(0);
            $table->set('comment_id')->int()->defaultVal(0)->comment('评论id');
        });
        Schema::createTable(OrderLogModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('order_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('status')->int()->defaultVal(1);
            $table->set('remark')->varchar()->defaultVal('');
            $table->timestamp('created_at');
        });
        Schema::createTable(OrderAddressModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('order_id')->int()->notNull();
            $table->set('name')->varchar(30)->notNull();
            $table->set('region_id')->int()->notNull();
            $table->set('region_name')->varchar()->notNull();
            $table->set('tel')->char(11)->notNull();
            $table->set('address')->varchar()->notNull();
            $table->set('best_time')->varchar()->defaultVal('');
        });
        Schema::createTable(OrderCouponModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('order_id')->int()->notNull();
            $table->set('coupon_id')->int()->notNull();
            $table->set('name')->varchar()->defaultVal('');
            $table->set('type')->varchar()->defaultVal('');
        });
        Schema::createTable(OrderActivityModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('order_id')->int()->notNull();
            $table->set('product_id')->int()->defaultVal(0);
            $table->set('type')->int()->notNull();
            $table->set('amount')->decimal(8, 2)->defaultVal(0);
            $table->set('tag')->varchar()->defaultVal('');
            $table->set('name')->varchar()->defaultVal('');
        });
        Schema::createTable(OrderRefundModel::tableName(), function (Table $table) {
            $table->setComment('订单售后服务');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('order_id')->int()->notNull();
            $table->set('order_goods_id')->int()->defaultVal(0);
            $table->set('goods_id')->int()->notNull();
            $table->set('product_id')->int()->defaultVal(0);
            $table->set('title')->varchar()->notNull();
            $table->set('amount')->int()->defaultVal(1)->comment('数量');
            $table->set('type')->tinyint(2)->defaultVal(0);
            $table->set('status')->tinyint(2)->defaultVal(0);
            $table->set('reason')->varchar()->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('evidence')->varchar()->defaultVal('')->comment('证据,json格式');
            $table->set('explanation')->varchar()->defaultVal('')->comment('平台回复');
            $table->set('money')->decimal(10, 2)->defaultVal(0);
            $table->set('order_price')->decimal(10, 2)->defaultVal(0);
            $table->set('freight')->tinyint(2)
                ->defaultVal(0)->comment('退款方式');
            $table->timestamps();
        });
    }

    /**
     * 创建文章
     */
    public function createArticle(): void {
        Schema::createTable(ArticleModel::tableName(), function (Table $table) {
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

        Schema::createTable(ArticleCategoryModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('文章分类名');
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
        });
    }

    /**
     * 创建属性
     */
    public function createAttribute(): void {
        Schema::createTable(AttributeGroupModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->timestamps();
        });
        Schema::createTable(AttributeModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('group_id')->int()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('search_type')->tinyint(1)->defaultVal(0);
            $table->set('input_type')->tinyint(1)->defaultVal(0);
            $table->set('default_value')->varchar()->notNull()->defaultVal('');
            $table->set('position')->tinyint(3)->defaultVal(99);
        });
        Schema::createTable(GoodsAttributeModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('goods_id')->int()->defaultVal(0);
            $table->set('attribute_id')->int()->notNull();
            $table->set('value')->varchar()->notNull();
            $table->set('price')->decimal(10, 2)->defaultVal(0)->comment('附加服务，多选不算在');
        });
        Schema::createTable(ProductModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('goods_id')->int()->notNull();
            $table->set('price')->decimal(10, 2)->defaultVal(0);
            $table->set('market_price')->decimal(10, 2)->defaultVal(0);
            $table->set('stock')->int()->defaultVal(1);
            $table->set('weight')->float(8, 3)->defaultVal(0);
            $table->set('series_number')->varchar(50)->defaultVal('');
            $table->set('attributes')->varchar(100)->defaultVal('');
        });
    }

    /**
     * 创建物流
     */
    public function createShipping(): void {
        Schema::createTable(ShippingModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('code')->varchar(30)->notNull();
            $table->set('method')->tinyint(2)->defaultVal(0)->comment('计费方式');
            $table->set('icon')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('position')->tinyint(2)->defaultVal(50);
            $table->timestamps();
        });
        Schema::createTable(ShippingGroupModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('shipping_id')->int()->notNull();
            $table->set('first_step')->float(10, 3)->defaultVal(0);
            $table->set('first_fee')->decimal(10, 2)->defaultVal(0);
            $table->set('additional')->float(10, 3)->defaultVal(0);
            $table->set('additional_fee')->decimal(10, 2)->defaultVal(0);
            $table->set('free_step')->float(10, 3)->defaultVal(0);
        });
        Schema::createTable(ShippingRegionModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('group_id')->int()->notNull();
            $table->set('region_id')->int()->notNull();
        });
    }

    /**
     * 创建评论
     */
    public function createComment(): void {
        Schema::createTable(CommentModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('item_type')->tinyint(2)->defaultVal(0);
            $table->set('item_id')->int()->notNull();
            $table->set('title')->varchar()->notNull();
            $table->set('content')->varchar()->notNull();
            $table->set('rank')->tinyint(2)->defaultVal(10);
            $table->timestamps();
        });
        Schema::createTable(CommentImageModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('comment_id')->int()->notNull();
            $table->set('image')->varchar()->notNull();
            $table->timestamps();
        });
    }

    /**
     * 创建商品
     */
    public function createGoods(): void {
        Schema::createTable(CategoryModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('分类名');
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('icon')->varchar(200);
            $table->set('banner')->varchar(200);
            $table->set('app_banner')->varchar(200);
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
        });
        Schema::createTable(CollectModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->timestamp('created_at');
        });
        Schema::createTable(BrandModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('分类名');
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('logo')->varchar(200)->comment('LOGO');
            $table->set('app_logo')->varchar(200)->comment('LOGO');
            $table->set('url')->varchar(200)->comment('官网');
        });
        Schema::createTable(GoodsModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('cat_id')->int()->notNull();
            $table->set('brand_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull()->comment('商品名');
            $table->set('series_number')->varchar(100)->notNull();
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('thumb')->varchar(200)->comment('缩略图');
            $table->set('picture')->varchar(200)->comment('主图');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('brief')->varchar(200)->comment('简介');
            $table->set('content')->text()->notNull()->comment('内容');
            $table->set('price')->decimal(8, 2)->defaultVal(0);
            $table->set('market_price')->decimal(8, 2)->defaultVal(0);
            $table->set('stock')->int()->defaultVal(1);
            $table->set('attribute_group_id')->int()->defaultVal(0);
            $table->set('weight')->float(8, 3)->defaultVal(0);
            $table->set('shipping_id')->int(10)->defaultVal(0)->comment('配送方式');
            $table->set('sales')->int()->defaultVal(0)->comment('销量');
            $table->set('is_best')->bool()->defaultVal(0);
            $table->set('is_hot')->bool()->defaultVal(0);
            $table->set('is_new')->bool()->defaultVal(0);
            $table->set('status')->tinyint(2)->defaultVal(10);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(CartModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('user_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->set('number')->int()->defaultVal(1);
            $table->set('price')->decimal(8, 2);
            $table->set('is_checked')->bool()->defaultVal(0)->comment('是否选中');
            $table->set('selected_activity')->int()->defaultVal(0)->comment('选择的活动');
        });
        Schema::createTable(GoodsIssue::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('goods_id')->int()->notNull();
            $table->set('question')->varchar()->notNull();
            $table->set('answer')->varchar()->defaultVal('');
        });
        Schema::createTable(GoodsGalleryModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('goods_id')->int()->notNull();
            $table->set('image')->varchar()->notNull();
        });
    }

    protected function createWarehouse(): void {
        Schema::createTable(WarehouseModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('tel')->varchar(30)->notNull();
            $table->set('address')->varchar()->defaultVal('');
        });
        Schema::createTable(WarehouseGoodsModel::tableName(), function (Table $table) {
            $table->set('warehouse_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->set('product_id')->int()->defaultVal(0);
        });
        Schema::createTable(WarehouseRegionModel::tableName(), function (Table $table) {
            $table->set('warehouse_id')->int()->notNull();
            $table->set('region_id')->int()->notNull();
        });
    }

    protected function createAd(): void {
        Schema::createTable(AdModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('position_id')->int()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(1);
            $table->set('url')->varchar()->notNull();
            $table->set('content')->varchar()->notNull();
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        });
        Schema::createTable(AdPositionModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('width')->varchar(20)->notNull();
            $table->set('height')->varchar(20)->notNull();
            $table->set('template')->varchar()->notNull();
            $table->timestamps();
        });
    }

    protected function createActivity() {
        Schema::createTable(ActivityModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull();
            $table->set('thumb')->varchar(200)->defaultVal('');
            $table->set('type')->tinyint(2)->defaultVal(ActivityModel::TYPE_AUCTION);
            $table->set('scope_type')->tinyint(1)->defaultVal(0)->comment('商品范围类型');
            $table->set('scope')->text()->notNull()->comment('商品范围值');
            $table->set('configure')->text()->notNull()->comment('其他配置信息');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        });
    }

}