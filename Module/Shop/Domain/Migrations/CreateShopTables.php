<?php
namespace Module\Shop\Domain\Migrations;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
use Module\Shop\Domain\Models\Activity\SeckillGoodsModel;
use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\Advertisement\AdModel;
use Module\Shop\Domain\Models\Advertisement\AdPositionModel;
use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;
use Module\Shop\Domain\Models\AttributeGroupModel;
use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\CollectModel;
use Module\Shop\Domain\Models\CommentImageModel;
use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\CouponLogModel;
use Module\Shop\Domain\Models\CouponModel;
use Module\Shop\Domain\Models\GoodsAttributeModel;
use Module\Shop\Domain\Models\GoodsGalleryModel;
use Module\Shop\Domain\Models\GoodsIssueModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\InvoiceModel;
use Module\Shop\Domain\Models\InvoiceTitleModel;
use Module\Shop\Domain\Models\Logistics\DeliveryGoodsModel;
use Module\Shop\Domain\Models\Logistics\DeliveryModel;
use Module\Shop\Domain\Models\NavigationModel;
use Module\Shop\Domain\Models\OrderActivityModel;
use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderCouponModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\OrderRefundModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ProductModel;
use Module\Shop\Domain\Models\RegionModel;
use Module\Shop\Domain\Models\ShippingGroupModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Models\ShippingRegionModel;
use Module\Shop\Domain\Models\WarehouseGoodsModel;
use Module\Shop\Domain\Models\WarehouseModel;
use Module\Shop\Domain\Models\WarehouseRegionModel;
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
            $table->timestamp('start_at')->comment('有效期开始时间');
            $table->timestamp('end_at')->comment('有效期结束时间');
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
            $table->set('invoice_type')->bool()->defaultVal(0)->comment('电子发票/纸质发票');
            $table->set('receiver_email')->varchar(100)->defaultVal('');
            $table->set('receiver_name')->varchar(100)->defaultVal('');
            $table->set('receiver_tel')->varchar(100)->defaultVal('');
            $table->set('receiver_region_id')->int()->defaultVal(0);
            $table->set('receiver_region_name')->varchar()->defaultVal('');
            $table->set('receiver_address')->varchar()->defaultVal('');
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
        $this->createLogistics();
        $this->createPay();
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
            $table->timestamp('pay_at')->comment('支付时间');
            $table->timestamp('shipping_at')->comment('发货时间');
            $table->timestamp('receive_at')->comment('签收时间');
            $table->timestamp('finish_at')->comment('完成时间');
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
            $table->set('amount')->int()->defaultVal(1);
            $table->set('price')->decimal(8, 2);
            $table->set('discount')->decimal(8, 2)->defaultVal(0)
                ->comment('已享受的折扣');
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
     * 物流发货
     */
    public function createLogistics(): void {
        Schema::createTable(DeliveryModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('order_id')->int()->notNull();
            $table->set('status')->int()->defaultVal(0);
            $table->set('shipping_id')->int()->defaultVal(0);
            $table->set('shipping_name')->varchar(30)->defaultVal(0);
            $table->set('shipping_fee')->decimal(8, 2)->defaultVal(0);
            $table->set('logistics_number')->varchar(30)->defaultVal('')->comment('物流单号');
            $table->set('name')->varchar(30)->notNull();
            $table->set('region_id')->int()->notNull();
            $table->set('region_name')->varchar()->notNull();
            $table->set('tel')->char(11)->notNull();
            $table->set('address')->varchar()->notNull();
            $table->set('best_time')->varchar()->defaultVal('');
            $table->timestamps();
        });
        Schema::createTable(DeliveryGoodsModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('delivery_id')->int()->notNull();
            $table->set('order_goods_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull()->comment('商品名');
            $table->set('series_number')->varchar(100)->notNull();
            $table->set('amount')->int()->defaultVal(1);
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
            $table->set('amount')->int()->defaultVal(1);
            $table->set('price')->decimal(8, 2);
            $table->set('is_checked')->bool()->defaultVal(0)->comment('是否选中');
            $table->set('selected_activity')->int()->defaultVal(0)->comment('选择的活动');
        });
        Schema::createTable(GoodsIssueModel::tableName(), function (Table $table) {
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
            $table->set('status')->bool()->defaultVal(1)->comment('开启关闭');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        });
        Schema::createTable(ActivityTimeModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('title')->varchar(40)->notNull();
            $table->set('start_at')->time();
            $table->set('end_at')->time();
        });
        Schema::createTable(SeckillGoodsModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('act_id')->int()->unsigned()->notNull();
            $table->set('time_id')->int()->unsigned()->notNull();
            $table->set('goods_id')->int()->unsigned()->notNull();
            $table->set('price')->decimal(8, 2)->defaultVal(0);
            $table->set('amount')->smallInt(5)->defaultVal(0);
            $table->set('every_amount')->tinyint(3)->defaultVal(0);
        });
    }

    private function createPay(): void {
        Schema::createTable(PaymentModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('code')->varchar(30)->notNull();
            $table->set('icon')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('settings')->text();
        });
        Schema::createTable(PayLogModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('payment_id')->int()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('user_id')->int()->notNull();
            $table->set('data')->varchar()->defaultVal('')->comment('可以接受多个订单号');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('amount')->decimal(10, 2)->defaultVal(0);
            $table->timestamps();
        });
    }

}