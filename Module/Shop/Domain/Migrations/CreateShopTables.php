<?php
namespace Module\Shop\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\SEO\Domain\Option;
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
use Module\Shop\Domain\Models\WarehouseLogModel;
use Module\Shop\Domain\Models\WarehouseModel;
use Module\Shop\Domain\Models\WarehouseRegionModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Query\Builder;
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
            $table->id();
            $table->column('name')->varchar(30);
            $table->uint('region_id');
            $table->uint('user_id');
            $table->column('tel')->char(11);
            $table->column('address')->varchar();
            $table->timestamps();
        })->append(CouponModel::tableName(), function(Table $table) {
            $table->comment('优惠券');
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('thumb')->varchar()->default('');
            $table->uint('type', 2)->default(0)->comment('优惠类型');
            $table->uint('rule', 2)->default(0)->comment('使用的商品');
            $table->uint('rule_value', 2)->default(0)->comment('使用的商品');
            $table->column('min_money')->decimal(8, 2)->default(0)->comment('满多少可用');
            $table->column('money')->decimal(8, 2)->default(0)->comment('几折或优惠金额');
            $table->uint('send_type')->default(0)->comment('发放类型');
            $table->uint('send_value')->default(0)->comment('发放条件或数量');
            $table->uint('every_amount')->default(0)->comment('每个用户能领取数量');
            $table->timestamp('start_at')->comment('有效期开始时间');
            $table->timestamp('end_at')->comment('有效期结束时间');
            $table->timestamps();
        })->append(CouponLogModel::tableName(), function(Table $table) {
            $table->comment('优惠券记录');
            $table->id();
            $table->uint('coupon_id');
            $table->column('serial_number')->varchar(30)->default('');
            $table->uint('user_id')->default(0);
            $table->uint('order_id')->default(0);
            $table->timestamp('used_at');
            $table->timestamps();
        })->append(InvoiceModel::tableName(), function(Table $table) {
            $table->comment('开票记录');
            $table->id();
            $table->uint('title_type', 1)->default(0)->comment('发票抬头类型');
            $table->uint('type', 1)->default(0)->comment('发票类型');
            $table->column('title')->varchar(100)->comment('抬头');
            $table->column('tax_no')->varchar(20)->comment('税务登记号');
            $table->column('tel')->char(11)->comment('注册场所电话');
            $table->column('bank')->varchar(100)->comment('开户银行');
            $table->column('account')->varchar(60)->comment('基本开户账号');
            $table->column('address')->varchar()->comment('注册场所地址');
            $table->uint('user_id');
            $table->column('money')->decimal(10, 2)->default(0)->comment('开票金额');
            $table->uint('status', 2)->default(0)->comment('开票状态');
            $table->column('invoice_type')->bool()->default(0)->comment('电子发票/纸质发票');
            $table->column('receiver_email')->varchar(100)->default('');
            $table->column('receiver_name')->varchar(100)->default('');
            $table->column('receiver_tel')->varchar(100)->default('');
            $table->column('receiver_region_id')->int()->default(0);
            $table->column('receiver_region_name')->varchar()->default('');
            $table->column('receiver_address')->varchar()->default('');
            $table->timestamps();
        })->append(InvoiceTitleModel::tableName(), function(Table $table) {
            $table->comment('用户发票抬头');
            $table->id();
            $table->uint('title_type', 1)->default(0)->comment('发票抬头类型');
            $table->uint('type', 1)->default(0)->comment('发票类型');
            $table->column('title')->varchar(100)->comment('抬头');
            $table->column('tax_no')->varchar(20)->comment('税务登记号');
            $table->column('tel')->char(11)->comment('注册场所电话');
            $table->column('bank')->varchar(100)->comment('开户银行');
            $table->column('account')->varchar(60)->comment('基本开户账号');
            $table->column('address')->varchar()->comment('注册场所地址');
            $table->uint('user_id');
            $table->timestamps();
        })->append(CertificationModel::tableName(), function (Table $table) {
            $table->comment('用户实名表');
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(20)->comment('真实姓名');
            $table->column('sex')->enum(['男', '女'])->default('男')->comment('性别');
            $table->column('country')->varchar(20)->default('')->comment('国家或地区');
            $table->uint('type', 1)->default(0)->comment('证件类型');
            $table->column('card_no')->varchar(30)->comment('证件号码');
            $table->column('expiry_date')->varchar(30)->default('')->comment('证件有效期');
            $table->column('profession')->varchar(30)->default('')->comment('行业');
            $table->column('address')->varchar(200)->default('')->comment('地址');
            $table->column('front_side')->varchar(200)->default('')->comment('正面照');
            $table->column('back_side')->varchar(200)->default('')->comment('反面照');
            $table->uint('status', 2)->default(0)->comment('审核状态');
            $table->timestamps();
        })->append(BankCardModel::tableName(), function (Table $table) {
            $table->comment('用户银行卡表');
            $table->id();
            $table->uint('user_id');
            $table->column('bank')->varchar(50)->comment('银行名');
            $table->uint('type', 1)->default(0)->comment('卡类型: 0 储蓄卡 1 信用卡');
            $table->column('card_no')->varchar(30)->comment('卡号码');
            $table->column('expiry_date')->varchar(30)->default('')->comment('卡有效期');
            $table->uint('status', 2)->default(0)->comment('审核状态');
            $table->timestamps();
        })->append(AffiliateLogModel::tableName(), function (Table $table) {
            $table->comment('用户分销记录表');
            $table->id();
            $table->uint('user_id');
            $table->uint('item_type', 1)->default(0)->comment('类型: 0 用户 1 订单');
            $table->uint('item_id')->comment('订单号/被推荐的用户');
            $table->column('order_sn')->varchar(30)->default('')->comment('订单号');
            $table->column('order_amount')->decimal(8, 2)->default(0)->comment('卡有效期');
            $table->column('money')->decimal(8, 2)->default(0)->comment('卡有效期');
            $table->uint('status', 2)->default(0)->comment('审核状态');
            $table->timestamps();
        });
        $this->createAttribute();

        $this->createComment();


        $this->append(NavigationModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('type')->varchar(10)->default('middle');
            $table->column('name')->varchar(100);
            $table->column('url')->varchar(200);
            $table->column('target')->varchar(10);
            $table->column('visible')->bool()->default(1);
            $table->uint('position', 2)->default(99);
        });

        $this->createGoods();
        $this->createOrder();
        $this->createLogistics();
        $this->createPay();
        $this->append(RegionModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar(30);
            $table->uint('parent_id')->default(0);
            $table->column('full_name')->varchar(100)->default('');
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
            $table->id();
            $table->column('series_number')->varchar(100);
            $table->uint('user_id');
            $table->uint('status')->default(0);
            $table->uint('payment_id')->default(0);
            $table->column('payment_name')->varchar(30)->default(0);
            $table->column('shipping_id')->int()->default(0);
            $table->column('invoice_id')->int()->default(0)->comment('发票');
            $table->column('shipping_name')->varchar(30)->default(0);
            $table->column('goods_amount')->decimal(8, 2)->default(0);
            $table->column('order_amount')->decimal(8, 2)->default(0);
            $table->column('discount')->decimal(8, 2)->default(0);
            $table->column('shipping_fee')->decimal(8, 2)->default(0);
            $table->column('pay_fee')->decimal(8, 2)->default(0);
            $table->uint('reference')->default(0)->comment('推荐人');
            $table->timestamp('pay_at')->comment('支付时间');
            $table->timestamp('shipping_at')->comment('发货时间');
            $table->timestamp('receive_at')->comment('签收时间');
            $table->timestamp('finish_at')->comment('完成时间');
            $table->timestamps();
        })->append(OrderGoodsModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('goods_id');
            $table->uint('product_id')->default(0);
            $table->uint('user_id');
            $table->column('name')->varchar(100)->comment('商品名');
            $table->column('series_number')->varchar(100);
            $table->column('thumb')->varchar(200)->comment('缩略图');
            $table->uint('amount')->default(1);
            $table->column('price')->decimal(8, 2);
            $table->column('discount')->decimal(8, 2)->default(0)
                ->comment('已享受的折扣');
            $table->column('refund_id')->int()->default(0);
            $table->uint('status')->default(0);
            $table->column('after_sale_status')->int()->default(0);
            $table->column('comment_id')->int()->default(0)->comment('评论id');
            $table->column('type_remark')->varchar()->default('')->comment('商品类型备注信息');
        })->append(OrderLogModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('user_id');
            $table->uint('status')->default(1);
            $table->column('remark')->varchar()->default('');
            $table->timestamp('created_at');
        })->append(OrderAddressModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->column('name')->varchar(30);
            $table->uint('region_id');
            $table->column('region_name')->varchar();
            $table->column('tel')->char(11);
            $table->column('address')->varchar();
            $table->column('best_time')->varchar()->default('');
        })->append(OrderCouponModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('coupon_id');
            $table->column('name')->varchar()->default('');
            $table->column('type')->varchar()->default('');
        })->append(OrderActivityModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('product_id')->default(0);
            $table->uint('type');
            $table->column('amount')->decimal(8, 2)->default(0);
            $table->column('tag')->varchar()->default('');
            $table->column('name')->varchar()->default('');
        })->append(OrderRefundModel::tableName(), function (Table $table) {
            $table->comment('订单售后服务');
            $table->id();
            $table->uint('user_id');
            $table->uint('order_id');
            $table->uint('order_goods_id')->default(0);
            $table->uint('goods_id');
            $table->uint('product_id')->default(0);
            $table->column('title')->varchar();
            $table->uint('amount')->default(1)->comment('数量');
            $table->uint('type', 2)->default(0);
            $table->uint('status', 2)->default(0);
            $table->column('reason')->varchar()->default('');
            $table->column('description')->varchar()->default('');
            $table->column('evidence')->varchar()->default('')->comment('证据,json格式');
            $table->column('explanation')->varchar()->default('')->comment('平台回复');
            $table->column('money')->decimal(10, 2)->default(0);
            $table->column('order_price')->decimal(10, 2)->default(0);
            $table->uint('freight', 2)
                ->default(0)->comment('退款方式');
            $table->timestamps();
        });
    }

    public function seed() {
        RoleRepository::newRole('shop_admin', '商城管理员');
        Option::group('商城设置', function () {
            return [
                [
                    'name' => '开启游客购买',
                    'code' => 'shop_guest_buy',
                    'type' => 'switch',
                    'value' => 0,
                ],
                [
                    'name' => '开启仓库',
                    'code' => 'shop_warehouse',
                    'type' => 'switch',
                    'value' => 0,
                ],
                [
                    'name' => '扣库存时间',
                    'code' => 'shop_store',
                    'type' => 'radio',
                    'value' => 0,
                    'default_value' => "不扣库存\n下单时\n支付时\n发货时"
                ],
            ];
        });
        $this->findOrNewById(AdPositionModel::query(), [
            'id' => 1,
            'name' => 'PC 首页 banner',
            'width' => '100%',
            'height' => '100%',
            'template' => '{url}',
        ]);
        $this->findOrNewById(AdPositionModel::query(), [
            'id' => 2,
            'name' => 'Mobile 首页 banner',
            'width' => '100%',
            'height' => '100%',
            'template' => '{url}',
        ]);
        $this->findOrNewById(ArticleCategoryModel::query(), [
            'id' => 1,
            'name' => '通知中心',
        ]);
        $this->findOrNewById(ArticleCategoryModel::query(), [
            'id' => 2,
            'name' => '帮助中心',
        ]);
    }

    private function findOrNewById(Builder $query, array $data) {
        $count = $query->where('id', $data['id'])->count();
        if ($count > 0) {
            return;
        }
        $query->insert($data);
    }

    /**
     * 物流发货
     */
    public function createLogistics(): void {
        $this->append(DeliveryModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('order_id');
            $table->uint('status')->default(0);
            $table->column('shipping_id')->int()->default(0);
            $table->column('shipping_name')->varchar(30)->default(0);
            $table->column('shipping_fee')->decimal(8, 2)->default(0);
            $table->column('logistics_number')->varchar(30)->default('')->comment('物流单号');
            $table->column('logistics_content')->text()->comment('物流信息');
            $table->column('name')->varchar(30);
            $table->uint('region_id');
            $table->column('region_name')->varchar();
            $table->column('tel')->char(11);
            $table->column('address')->varchar();
            $table->column('best_time')->varchar()->default('');
            $table->timestamps();
        })->append(DeliveryGoodsModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('delivery_id')->int();
            $table->uint('order_goods_id');
            $table->uint('goods_id');
            $table->uint('product_id')->default(0);
            $table->column('name')->varchar(100)->comment('商品名');
            $table->column('thumb')->varchar();
            $table->column('series_number')->varchar(100);
            $table->uint('amount')->default(1);
            $table->column('type_remark')->varchar()->default('')->comment('商品类型备注信息');
        });
    }

    /**
     * 创建文章
     */
    public function createArticle(): void {
        $this->append(ArticleModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('cat_id');
            $table->column('title')->varchar(100)->comment('文章名');
            $table->column('keywords')->varchar(200)->comment('关键字');
            $table->column('thumb')->varchar(200)->comment('缩略图');
            $table->column('description')->varchar(200)->comment('关键字');
            $table->column('brief')->varchar(200)->comment('简介');
            $table->column('url')->varchar(200)->comment('链接');
            $table->column('file')->varchar(200)->comment('下载内容');
            $table->column('content')->text()->comment('内容');
            $table->timestamps();
        })->append(ArticleCategoryModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(100)->comment('文章分类名');
            $table->column('keywords')->varchar(200)->comment('关键字');
            $table->column('description')->varchar(200)->comment('关键字');
            $table->uint('parent_id')->default(0);
            $table->uint('position', 3)->default(99);
        });
    }

    /**
     * 创建属性
     */
    public function createAttribute(): void {
        $this->append(AttributeGroupModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(30);
            $table->timestamps();
        })->append(AttributeModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(30);
            $table->uint('group_id');
            $table->uint('type', 1)->default(0);
            $table->uint('search_type', 1)->default(0);
            $table->uint('input_type', 1)->default(0);
            $table->column('default_value')->varchar()->default('');
            $table->uint('position', 3)->default(99);
        })->append(GoodsAttributeModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id')->default(0);
            $table->column('attribute_id')->int();
            $table->column('value')->varchar();
            $table->column('price')->decimal(10, 2)->default(0)->comment('附加服务，多选不算在');
        })->append(ProductModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->column('price')->decimal(10, 2)->default(0);
            $table->column('market_price')->decimal(10, 2)->default(0);
            $table->column('stock')->int()->default(1);
            $table->column('weight')->float(8, 3)->default(0);
            $table->column('series_number')->varchar(50)->default('');
            $table->column('attributes')->varchar(100)->default('');
        });
    }

    /**
     * 创建物流
     */
    public function createShipping(): void {
        $this->append(ShippingModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('code')->varchar(30);
            $table->uint('method', 2)->default(0)->comment('计费方式');
            $table->column('icon')->varchar(100)->default('');
            $table->column('description')->varchar()->default('');
            $table->uint('position', 2)->default(50);
            $table->timestamps();
        })->append(ShippingGroupModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('shipping_id')->int();
            $table->column('is_all')->bool()->default(0);
            $table->column('first_step')->float(10, 3)->default(0);
            $table->column('first_fee')->decimal(10, 2)->default(0);
            $table->column('additional')->float(10, 3)->default(0);
            $table->column('additional_fee')->decimal(10, 2)->default(0);
            $table->column('free_step')->float(10, 3)->default(0);
        })->append(ShippingRegionModel::tableName(), function (Table $table) {
            $table->uint('shipping_id');
            $table->uint('group_id');
            $table->uint('region_id');
        });
    }

    /**
     * 创建评论
     */
    public function createComment(): void {
        $this->append(CommentModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('item_type', 2)->default(0);
            $table->uint('item_id');
            $table->column('title')->varchar();
            $table->column('content')->varchar();
            $table->uint('rank', 2)->default(10);
            $table->timestamps();
        })->append(CommentImageModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('comment_id');
            $table->column('image')->varchar();
            $table->timestamps();
        });
    }

    /**
     * 创建商品
     */
    public function createGoods(): void {
        $this->append(CategoryModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(100)->comment('分类名');
            $table->column('keywords')->varchar(200)->comment('关键字');
            $table->column('description')->varchar(200)->comment('关键字');
            $table->column('icon')->varchar(200);
            $table->column('banner')->varchar(200);
            $table->column('app_banner')->varchar(200);
            $table->uint('parent_id')->default(0);
            $table->uint('position', 3)->default(99);
        })->append(CollectModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('goods_id');
            $table->timestamp('created_at');
        })->append(BrandModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(100)->comment('分类名');
            $table->column('keywords')->varchar(200)->comment('关键字');
            $table->column('description')->varchar(200)->comment('关键字');
            $table->column('logo')->varchar(200)->comment('LOGO');
            $table->column('app_logo')->varchar(200)->comment('LOGO');
            $table->column('url')->varchar(200)->comment('官网');
        })->append(GoodsModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('cat_id');
            $table->column('brand_id')->int();
            $table->column('name')->varchar(100)->comment('商品名');
            $table->column('series_number')->varchar(100);
            $table->column('keywords')->varchar(200)->comment('关键字');
            $table->column('thumb')->varchar(200)->comment('缩略图');
            $table->column('picture')->varchar(200)->comment('主图');
            $table->column('description')->varchar(200)->comment('关键字');
            $table->column('brief')->varchar(200)->comment('简介');
            $table->column('content')->text()->comment('内容');
            $table->column('price')->decimal(8, 2)->default(0);
            $table->column('market_price')->decimal(8, 2)->default(0);
            $table->column('stock')->int()->default(1);
            $table->uint('attribute_group_id')->default(0);
            $table->column('weight')->float(8, 3)->default(0);
            $table->uint('shipping_id')->default(0)->comment('配送方式');
            $table->column('sales')->int()->default(0)->comment('销量');
            $table->column('is_best')->bool()->default(0);
            $table->column('is_hot')->bool()->default(0);
            $table->column('is_new')->bool()->default(0);
            $table->uint('status', 2)->default(10);
            $table->column('admin_note')->varchar()->default('')->comment('管理员备注，只后台显示');
            $table->uint('type', 1)->default(0)->comment('商品类型');
            $table->uint('position', 1)->default(99)->comment('排序');
            $table->uint('dynamic_position', 1)
                ->default(0)->comment('动态排序');
            $table->softDeletes();
            $table->timestamps();
        })->append(GoodsCardModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->column('card_no')->varchar();
            $table->column('card_pwd')->varchar();
            $table->uint('order_id')->default(0);
        })->append(CartModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('type', 1)->default(0);
            $table->uint('user_id');
            $table->uint('goods_id');
            $table->uint('product_id');
            $table->uint('amount')->default(1);
            $table->column('price')->decimal(8, 2);
            $table->column('is_checked')->bool()->default(0)->comment('是否选中');
            $table->uint('selected_activity')->default(0)->comment('选择的活动');
            $table->column('attribute_id')->varchar()->default('')->comment('选择的属性');
            $table->column('attribute_value')->varchar()->default('')->comment('选择的属性值');
            $table->timestamp('expired_at')->comment('过期时间');
        })->append(GoodsIssueModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->column('question')->varchar();
            $table->column('answer')->varchar()->default('');
        })->append(GoodsGalleryModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->column('image')->varchar();
        });
    }

    protected function createWarehouse(): void {
        $this->append(WarehouseModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('tel')->varchar(30);
            $table->column('link_user')->varchar(30)->default('');
            $table->column('address')->varchar()->default('');
            $table->column('longitude')->varchar(50)->comment('经度');
            $table->column('latitude')->varchar(50)->comment('纬度');
            $table->column('remark')->varchar()->default('');
            $table->timestamps();
        })->append(WarehouseGoodsModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('warehouse_id');
            $table->uint('goods_id');
            $table->uint('product_id')->default(0);
            $table->uint('amount')->default(0);
        })->append(WarehouseRegionModel::tableName(), function (Table $table) {
            $table->uint('warehouse_id');
            $table->uint('region_id');
        })->append(WarehouseLogModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('warehouse_id');
            $table->uint('user_id');
            $table->uint('goods_id');
            $table->uint('product_id')->default(0);
            $table->uint('amount');
            $table->uint('order_id')->default(0);
            $table->column('remark')->varchar()->default('');
            $table->timestamp('created_at');
        });
    }

    protected function createAd(): void {
        $this->append(AdModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(30);
            $table->uint('position_id');
            $table->uint('type', 1)->default(1);
            $table->column('url')->varchar();
            $table->column('content')->varchar();
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        })->append(AdPositionModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('width')->varchar(20);
            $table->column('height')->varchar(20);
            $table->column('template')->varchar();
            $table->timestamps();
        });
    }

    protected function createActivity() {
        $this->append(ActivityModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(40);
            $table->column('thumb')->varchar(200)->default('');
            $table->column('description')->varchar()->default('');
            $table->uint('type', 2)->default(ActivityModel::TYPE_AUCTION);
            $table->uint('scope_type', 1)->default(0)->comment('商品范围类型');
            $table->column('scope')->text()->comment('商品范围值');
            $table->column('configure')->text()->comment('其他配置信息');
            $table->column('status')->bool()->default(1)->comment('开启关闭');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        })->append(ActivityTimeModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('title')->varchar(40);
            $table->column('start_at')->time();
            $table->column('end_at')->time();
        })->append(SeckillGoodsModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('act_id');
            $table->uint('time_id');
            $table->uint('goods_id')->unsigned();
            $table->column('price')->decimal(8, 2)->default(0);
            $table->uint('amount', 5)->default(0);
            $table->uint('every_amount', 2)->default(0);
        });
    }

    private function createPay(): void {
        $this->append(PaymentModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('code')->varchar(30);
            $table->column('icon')->varchar(100)->default('');
            $table->column('description')->varchar()->default('');
            $table->column('settings')->text();
        })->append(PayLogModel::tableName(), function (Table $table) {
            $table->column('id')->pk()->ai(10000001);
            $table->uint('payment_id');
            $table->column('payment_name')->varchar(30)->default('');
            $table->uint('type', 1)->default(0);
            $table->uint('user_id');
            $table->column('data')->varchar()->default('')->comment('可以接受多个订单号');
            $table->uint('status', 2)->default(0);
            $table->column('amount')->decimal(10, 2)->default(0);
            $table->column('currency')->varchar(10)->default('')->comment('货币');
            $table->column('currency_money')->decimal(10, 2)->default(0)->comment('货币金额');
            $table->column('trade_no')->varchar(100)->default('')->comment('外部订单号');
            $table->timestamp('begin_at')->comment('开始时间');
            $table->timestamp('confirm_at')->comment('确认支付时间');
            $table->timestamps();
        });
    }

}