<?php
namespace Module\Shop\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
use Module\Shop\Domain\Models\Activity\SeckillGoodsModel;
use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\Advertisement\AdModel;
use Module\Shop\Domain\Models\Advertisement\AdPositionModel;
use Module\Shop\Domain\Models\AffiliateLogModel;
use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;
use Module\Shop\Domain\Models\AttributeGroupModel;
use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\BankCardModel;
use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\CertificationModel;
use Module\Shop\Domain\Models\CollectModel;
use Module\Shop\Domain\Models\CommentImageModel;
use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\CouponLogModel;
use Module\Shop\Domain\Models\CouponModel;
use Module\Shop\Domain\Models\GoodsAttributeModel;
use Module\Shop\Domain\Models\GoodsCardModel;
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
        $this->append(AddressModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('region_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('tel')->char(11)->notNull();
            $table->set('address')->varchar()->notNull();
            $table->timestamps();
        })->append(CouponModel::tableName(), function(Table $table) {
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
        })->append(CouponLogModel::tableName(), function(Table $table) {
            $table->setComment('优惠券记录');
            $table->set('id')->pk()->ai();
            $table->set('coupon_id')->int()->notNull();
            $table->set('serial_number')->varchar(30)->defaultVal('');
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('order_id')->int()->defaultVal(0);
            $table->timestamp('used_at');
            $table->timestamps();
        })->append(InvoiceModel::tableName(), function(Table $table) {
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
        })->append(InvoiceTitleModel::tableName(), function(Table $table) {
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
        })->append(CertificationModel::tableName(), function (Table $table) {
            $table->setComment('用户实名表');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(20)->notNull()->comment('真实姓名');
            $table->set('sex')->enum(['男', '女'])->defaultVal('男')->comment('性别');
            $table->set('country')->varchar(20)->defaultVal('')->comment('国家或地区');
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('证件类型');
            $table->set('card_no')->varchar(30)->notNull()->comment('证件号码');
            $table->set('expiry_date')->varchar(30)->defaultVal('')->comment('证件有效期');
            $table->set('profession')->varchar(30)->defaultVal('')->comment('行业');
            $table->set('address')->varchar(200)->defaultVal('')->comment('地址');
            $table->set('front_side')->varchar(200)->defaultVal('')->comment('正面照');
            $table->set('back_side')->varchar(200)->defaultVal('')->comment('反面照');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('审核状态');
            $table->timestamps();
        })->append(BankCardModel::tableName(), function (Table $table) {
            $table->setComment('用户银行卡表');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('bank')->varchar(50)->notNull()->comment('银行名');
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('卡类型: 0 储蓄卡 1 信用卡');
            $table->set('card_no')->varchar(30)->notNull()->comment('卡号码');
            $table->set('expiry_date')->varchar(30)->defaultVal('')->comment('卡有效期');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('审核状态');
            $table->timestamps();
        })->append(AffiliateLogModel::tableName(), function (Table $table) {
            $table->setComment('用户分销记录表');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('item_type')->tinyint(1)->defaultVal(0)->comment('类型: 0 用户 1 订单');
            $table->set('item_id')->int()->notNull()->comment('订单号/被推荐的用户');
            $table->set('order_sn')->varchar(30)->defaultVal('')->comment('订单号');
            $table->set('order_amount')->decimal(8, 2)->defaultVal(0)->comment('卡有效期');
            $table->set('money')->decimal(8, 2)->defaultVal(0)->comment('卡有效期');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('审核状态');
            $table->timestamps();
        });
        $this->createAttribute();

        $this->createComment();


        $this->append(NavigationModel::tableName(), function(Table $table) {
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
        $this->append(RegionModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('full_name')->varchar(100)->defaultVal('');
        });
        $this->createShipping();
        $this->createWarehouse();

        $this->createActivity();
        parent::up();
    }



    /**
     * 创建订单
     */
    public function createOrder(): void {
        $this->append(OrderModel::tableName(), function (Table $table) {
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
            $table->set('reference')->int()->defaultVal(0)->comment('推荐人');
            $table->timestamp('pay_at')->comment('支付时间');
            $table->timestamp('shipping_at')->comment('发货时间');
            $table->timestamp('receive_at')->comment('签收时间');
            $table->timestamp('finish_at')->comment('完成时间');
            $table->timestamps();
        })->append(OrderGoodsModel::tableName(), function (Table $table) {
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
            $table->set('type_remark')->varchar()->defaultVal('')->comment('商品类型备注信息');
        })->append(OrderLogModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('order_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('status')->int()->defaultVal(1);
            $table->set('remark')->varchar()->defaultVal('');
            $table->timestamp('created_at');
        })->append(OrderAddressModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('order_id')->int()->notNull();
            $table->set('name')->varchar(30)->notNull();
            $table->set('region_id')->int()->notNull();
            $table->set('region_name')->varchar()->notNull();
            $table->set('tel')->char(11)->notNull();
            $table->set('address')->varchar()->notNull();
            $table->set('best_time')->varchar()->defaultVal('');
        })->append(OrderCouponModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('order_id')->int()->notNull();
            $table->set('coupon_id')->int()->notNull();
            $table->set('name')->varchar()->defaultVal('');
            $table->set('type')->varchar()->defaultVal('');
        })->append(OrderActivityModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('order_id')->int()->notNull();
            $table->set('product_id')->int()->defaultVal(0);
            $table->set('type')->int()->notNull();
            $table->set('amount')->decimal(8, 2)->defaultVal(0);
            $table->set('tag')->varchar()->defaultVal('');
            $table->set('name')->varchar()->defaultVal('');
        })->append(OrderRefundModel::tableName(), function (Table $table) {
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

    public function seed() {
        RoleRepository::newRole('shop_admin', '商城管理员');
        $count = AdPositionModel::query()->whereIn('id', [1,2])->count();
        if ($count < 1) {
            AdPositionModel::query()
                ->insert([
                    [
                        'id' => 1,
                        'name' => 'PC 首页 banner',
                        'width' => '100%',
                        'height' => '100%',
                        'template' => '{url}',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Mobile 首页 banner',
                        'width' => '100%',
                        'height' => '100%',
                        'template' => '{url}',
                    ]
                ]);
        }
        $count = ArticleCategoryModel::query()->whereIn('id', [1, 2])->count();
        if ($count < 1) {
            ArticleCategoryModel::query()
                ->insert([
                    [
                        'id' => 1,
                        'name' => '通知中心',
                    ],
                    [
                        'id' => 2,
                        'name' => '帮助中心',
                    ],
                ]);
        }
    }

    /**
     * 物流发货
     */
    public function createLogistics(): void {
        $this->append(DeliveryModel::tableName(), function (Table $table) {
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
        })->append(DeliveryGoodsModel::tableName(), function (Table $table) {
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
        $this->append(ArticleModel::tableName(), function (Table $table) {
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
        })->append(ArticleCategoryModel::tableName(), function (Table $table) {
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
        $this->append(AttributeGroupModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->timestamps();
        })->append(AttributeModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('group_id')->int()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('search_type')->tinyint(1)->defaultVal(0);
            $table->set('input_type')->tinyint(1)->defaultVal(0);
            $table->set('default_value')->varchar()->notNull()->defaultVal('');
            $table->set('position')->tinyint(3)->defaultVal(99);
        })->append(GoodsAttributeModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('goods_id')->int()->defaultVal(0);
            $table->set('attribute_id')->int()->notNull();
            $table->set('value')->varchar()->notNull();
            $table->set('price')->decimal(10, 2)->defaultVal(0)->comment('附加服务，多选不算在');
        })->append(ProductModel::tableName(), function (Table $table) {
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
        $this->append(ShippingModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('code')->varchar(30)->notNull();
            $table->set('method')->tinyint(2)->defaultVal(0)->comment('计费方式');
            $table->set('icon')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('position')->tinyint(2)->defaultVal(50);
            $table->timestamps();
        })->append(ShippingGroupModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('shipping_id')->int()->notNull();
            $table->set('first_step')->float(10, 3)->defaultVal(0);
            $table->set('first_fee')->decimal(10, 2)->defaultVal(0);
            $table->set('additional')->float(10, 3)->defaultVal(0);
            $table->set('additional_fee')->decimal(10, 2)->defaultVal(0);
            $table->set('free_step')->float(10, 3)->defaultVal(0);
        })->append(ShippingRegionModel::tableName(), function (Table $table) {
            $table->set('shipping_id')->int()->notNull();
            $table->set('group_id')->int()->notNull();
            $table->set('region_id')->int()->notNull();
        });
    }

    /**
     * 创建评论
     */
    public function createComment(): void {
        $this->append(CommentModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('item_type')->tinyint(2)->defaultVal(0);
            $table->set('item_id')->int()->notNull();
            $table->set('title')->varchar()->notNull();
            $table->set('content')->varchar()->notNull();
            $table->set('rank')->tinyint(2)->defaultVal(10);
            $table->timestamps();
        })->append(CommentImageModel::tableName(), function (Table $table) {
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
        $this->append(CategoryModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('分类名');
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('icon')->varchar(200);
            $table->set('banner')->varchar(200);
            $table->set('app_banner')->varchar(200);
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
        })->append(CollectModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->timestamp('created_at');
        })->append(BrandModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('分类名');
            $table->set('keywords')->varchar(200)->comment('关键字');
            $table->set('description')->varchar(200)->comment('关键字');
            $table->set('logo')->varchar(200)->comment('LOGO');
            $table->set('app_logo')->varchar(200)->comment('LOGO');
            $table->set('url')->varchar(200)->comment('官网');
        })->append(GoodsModel::tableName(), function (Table $table) {
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
            $table->set('admin_note')->varchar()->defaultVal('')->comment('管理员备注，只后台显示');
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('商品类型');
            $table->set('position')->tinyint(1)->defaultVal(99)->comment('排序');
            $table->set('dynamic_position')->tinyint(1)
                ->defaultVal(0)->comment('动态排序');
            $table->softDeletes();
            $table->timestamps();
        })->append(GoodsCardModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('goods_id')->int()->notNull();
            $table->set('card_no')->varchar()->notNull();
            $table->set('card_pwd')->varchar()->notNull();
            $table->set('order_id')->int()->defaultVal(0);
        })->append(CartModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('user_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->set('amount')->int()->defaultVal(1);
            $table->set('price')->decimal(8, 2);
            $table->set('is_checked')->bool()->defaultVal(0)->comment('是否选中');
            $table->set('selected_activity')->int()->defaultVal(0)->comment('选择的活动');
        })->append(GoodsIssueModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('goods_id')->int()->notNull();
            $table->set('question')->varchar()->notNull();
            $table->set('answer')->varchar()->defaultVal('');
        })->append(GoodsGalleryModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('goods_id')->int()->notNull();
            $table->set('image')->varchar()->notNull();
        });
    }

    protected function createWarehouse(): void {
        $this->append(WarehouseModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('tel')->varchar(30)->notNull();
            $table->set('address')->varchar()->defaultVal('');
        })->append(WarehouseGoodsModel::tableName(), function (Table $table) {
            $table->set('warehouse_id')->int()->notNull();
            $table->set('goods_id')->int()->notNull();
            $table->set('product_id')->int()->defaultVal(0);
        })->append(WarehouseRegionModel::tableName(), function (Table $table) {
            $table->set('warehouse_id')->int()->notNull();
            $table->set('region_id')->int()->notNull();
        });
    }

    protected function createAd(): void {
        $this->append(AdModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('position_id')->int()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(1);
            $table->set('url')->varchar()->notNull();
            $table->set('content')->varchar()->notNull();
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        })->append(AdPositionModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('width')->varchar(20)->notNull();
            $table->set('height')->varchar(20)->notNull();
            $table->set('template')->varchar()->notNull();
            $table->timestamps();
        });
    }

    protected function createActivity() {
        $this->append(ActivityModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull();
            $table->set('thumb')->varchar(200)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('type')->tinyint(2)->defaultVal(ActivityModel::TYPE_AUCTION);
            $table->set('scope_type')->tinyint(1)->defaultVal(0)->comment('商品范围类型');
            $table->set('scope')->text()->notNull()->comment('商品范围值');
            $table->set('configure')->text()->notNull()->comment('其他配置信息');
            $table->set('status')->bool()->defaultVal(1)->comment('开启关闭');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        })->append(ActivityTimeModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('title')->varchar(40)->notNull();
            $table->set('start_at')->time();
            $table->set('end_at')->time();
        })->append(SeckillGoodsModel::tableName(), function (Table $table) {
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
        $this->append(PaymentModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('code')->varchar(30)->notNull();
            $table->set('icon')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('settings')->text();
        })->append(PayLogModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai(10000001);
            $table->set('payment_id')->int()->notNull();
            $table->set('payment_name')->varchar(30)->defaultVal('');
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('user_id')->int()->notNull();
            $table->set('data')->varchar()->defaultVal('')->comment('可以接受多个订单号');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('amount')->decimal(10, 2)->defaultVal(0);
            $table->set('currency')->varchar(10)->defaultVal('')->comment('货币');
            $table->set('currency_money')->decimal(10, 2)->defaultVal(0)->comment('货币金额');
            $table->set('trade_no')->varchar(100)->defaultVal('')->comment('外部订单号');
            $table->timestamp('begin_at')->comment('开始时间');
            $table->timestamp('confirm_at')->comment('确认支付时间');
            $table->timestamps();
        });
    }

}