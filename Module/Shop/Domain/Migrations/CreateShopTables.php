<?php
namespace Module\Shop\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\SEO\Domain\Option;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
use Module\Shop\Domain\Models\Activity\AuctionLogModel;
use Module\Shop\Domain\Models\Activity\BargainLogModel;
use Module\Shop\Domain\Models\Activity\BargainUserModel;
use Module\Shop\Domain\Models\Activity\GroupBuyLogModel;
use Module\Shop\Domain\Models\Activity\LotteryLogModel;
use Module\Shop\Domain\Models\Activity\PresaleLogModel;
use Module\Shop\Domain\Models\Activity\SeckillGoodsModel;
use Module\Shop\Domain\Models\Activity\TrialLogModel;
use Module\Shop\Domain\Models\Activity\TrialReportModel;
use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\Advertisement\AdModel;
use Module\Shop\Domain\Models\Advertisement\AdPositionModel;
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
use Module\Shop\Domain\Models\GoodsMetaModel;
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
use Module\Shop\Domain\Plugin\Affiliate\AffiliateLogModel;
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
            $table->string('name', 30);
            $table->uint('region_id');
            $table->uint('user_id');
            $table->char('tel', 11);
            $table->string('address');
            $table->timestamps();
        })->append(CouponModel::tableName(), function(Table $table) {
            $table->comment('优惠券');
            $table->id();
            $table->string('name', 30);
            $table->string('thumb')->default('');
            $table->uint('type', 2)->default(0)->comment('优惠类型');
            $table->uint('rule', 2)->default(0)->comment('使用的商品');
            $table->string('rule_value')->default('')->comment('使用的商品');
            $table->decimal('min_money', 8, 2)->default(0)->comment('满多少可用');
            $table->decimal('money', 8, 2)->default(0)->comment('几折或优惠金额');
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
            $table->string('serial_number', 30)->default('');
            $table->uint('user_id')->default(0);
            $table->uint('order_id')->default(0);
            $table->timestamp('used_at');
            $table->timestamps();
        })->append(InvoiceModel::tableName(), function(Table $table) {
            $table->comment('开票记录');
            $table->id();
            $table->uint('title_type', 1)->default(0)->comment('发票抬头类型');
            $table->uint('type', 1)->default(0)->comment('发票类型');
            $table->string('title', 100)->comment('抬头');
            $table->string('tax_no', 20)->comment('税务登记号');
            $table->char('tel', 11)->comment('注册场所电话');
            $table->string('bank', 100)->comment('开户银行');
            $table->string('account', 60)->comment('基本开户账号');
            $table->string('address')->comment('注册场所地址');
            $table->uint('user_id');
            $table->decimal('money', 10, 2)->default(0)->comment('开票金额');
            $table->uint('status', 2)->default(0)->comment('开票状态');
            $table->bool('invoice_type')->default(0)->comment('电子发票/纸质发票');
            $table->string('receiver_email', 100)->default('');
            $table->string('receiver_name', 100)->default('');
            $table->string('receiver_tel', 100)->default('');
            $table->uint('receiver_region_id')->default(0);
            $table->string('receiver_region_name')->default('');
            $table->string('receiver_address')->default('');
            $table->timestamps();
        })->append(InvoiceTitleModel::tableName(), function(Table $table) {
            $table->comment('用户发票抬头');
            $table->id();
            $table->uint('title_type', 1)->default(0)->comment('发票抬头类型');
            $table->uint('type', 1)->default(0)->comment('发票类型');
            $table->string('title', 100)->comment('抬头');
            $table->string('tax_no', 20)->comment('税务登记号');
            $table->char('tel', 11)->comment('注册场所电话');
            $table->string('bank', 100)->comment('开户银行');
            $table->string('account', 60)->comment('基本开户账号');
            $table->string('address')->comment('注册场所地址');
            $table->uint('user_id');
            $table->timestamps();
        })->append(CertificationModel::tableName(), function (Table $table) {
            $table->comment('用户实名表');
            $table->id();
            $table->uint('user_id');
            $table->string('name', 20)->comment('真实姓名');
            $table->enum('sex', ['男', '女'])->default('男')->comment('性别');
            $table->string('country', 20)->default('')->comment('国家或地区');
            $table->uint('type', 1)->default(0)->comment('证件类型');
            $table->string('card_no', 30)->comment('证件号码');
            $table->string('expiry_date', 30)->default('')->comment('证件有效期');
            $table->string('profession', 30)->default('')->comment('行业');
            $table->string('address', 200)->default('')->comment('地址');
            $table->string('front_side', 200)->default('')->comment('正面照');
            $table->string('back_side', 200)->default('')->comment('反面照');
            $table->uint('status', 2)->default(0)->comment('审核状态');
            $table->timestamps();
        })->append(BankCardModel::tableName(), function (Table $table) {
            $table->comment('用户银行卡表');
            $table->id();
            $table->uint('user_id');
            $table->string('bank', 50)->comment('银行名');
            $table->uint('type', 1)->default(0)->comment('卡类型: 0 储蓄卡 1 信用卡');
            $table->string('card_no', 30)->comment('卡号码');
            $table->string('expiry_date', 30)->default('')->comment('卡有效期');
            $table->uint('status', 2)->default(0)->comment('审核状态');
            $table->timestamps();
        })->append(AffiliateLogModel::tableName(), function (Table $table) {
            $table->comment('用户分销记录表');
            $table->id();
            $table->uint('user_id');
            $table->uint('item_type', 1)->default(0)->comment('类型: 0 用户 1 订单');
            $table->uint('item_id')->comment('订单号/被推荐的用户');
            $table->string('order_sn', 30)->default('')->comment('订单号');
            $table->decimal('order_amount', 8, 2)->default(0)->comment('订单金额');
            $table->decimal('money', 8, 2)->default(0)->comment('佣金');
            $table->uint('status', 2)->default(0)->comment('审核状态');
            $table->timestamps();
        });
        $this->createAttribute();
        $this->createComment();
        $this->append(NavigationModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('type', 10)->default('middle');
            $table->string('name', 100);
            $table->string('url', 200);
            $table->string('target', 10);
            $table->bool('visible')->default(1);
            $table->uint('position', 2)->default(99);
        });

        $this->createGoods();
        $this->createOrder();
        $this->createLogistics();
        $this->createPay();
        $this->append(RegionModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->uint('parent_id')->default(0);
            $table->string('full_name', 100)->default('');
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
            $table->string('series_number', 100);
            $table->uint('user_id');
            $table->uint('status')->default(0);
            $table->uint('payment_id')->default(0);
            $table->string('payment_name', 30)->default(0);
            $table->uint('shipping_id')->default(0);
            $table->uint('invoice_id')->default(0)->comment('发票');
            $table->string('shipping_name', 30)->default(0);
            $table->decimal('goods_amount', 8, 2)->default(0);
            $table->decimal('order_amount', 8, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('shipping_fee', 8, 2)->default(0);
            $table->decimal('pay_fee', 8, 2)->default(0);
            $table->uint('reference_type', 1)->default(0)->comment('来源类型');
            $table->uint('reference_id')->default(0)->comment('来源相关id');
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
            $table->string('name', 100)->comment('商品名');
            $table->string('series_number', 100);
            $table->string('thumb', 200)->comment('缩略图');
            $table->uint('amount')->default(1);
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2)->default(0)
                ->comment('已享受的折扣');
            $table->uint('refund_id')->default(0);
            $table->uint('status')->default(0);
            $table->uint('after_sale_status')->default(0);
            $table->uint('comment_id')->default(0)->comment('评论id');
            $table->string('type_remark')->default('')->comment('商品类型备注信息');
        })->append(OrderLogModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('user_id');
            $table->uint('status')->default(1);
            $table->string('remark')->default('');
            $table->timestamp('created_at');
        })->append(OrderAddressModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->string('name', 30);
            $table->uint('region_id');
            $table->string('region_name');
            $table->char('tel', 11);
            $table->string('address');
            $table->string('best_time')->default('');
        })->append(OrderCouponModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('coupon_id');
            $table->string('name')->default('');
            $table->string('type')->default('');
        })->append(OrderActivityModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('product_id')->default(0);
            $table->uint('type');
            $table->decimal('amount', 8, 2)->default(0);
            $table->string('tag')->default('');
            $table->string('name')->default('');
        })->append(OrderRefundModel::tableName(), function (Table $table) {
            $table->comment('订单售后服务');
            $table->id();
            $table->uint('user_id');
            $table->uint('order_id');
            $table->uint('order_goods_id')->default(0);
            $table->uint('goods_id');
            $table->uint('product_id')->default(0);
            $table->string('title');
            $table->uint('amount')->default(1)->comment('数量');
            $table->uint('type', 2)->default(0);
            $table->uint('status', 2)->default(0);
            $table->string('reason')->default('');
            $table->string('description')->default('');
            $table->string('evidence')->default('')->comment('证据,json格式');
            $table->string('explanation')->default('')->comment('平台回复');
            $table->decimal('money', 10, 2)->default(0);
            $table->decimal('order_price', 10, 2)->default(0);
            $table->uint('freight', 2)
                ->default(0)->comment('退款方式');
            $table->timestamps();
        });
    }

    public function seed() {
        RoleRepository::newRole('shop_admin', '商城管理员', [
            'shop_manage' => '商城管理'
        ]);
        Option::group('商城设置', function () {
            return [
                [
                    'name' => '商城开启状态',
                    'code' => 'shop_open_status',
                    'type' => 'switch',
                    'value' => 1,
                ],
                [
                    'name' => '开启游客购买',
                    'code' => 'shop_guest_buy',
                    'type' => 'switch',
                    'value' => 0,
                    'visibility' => 2,
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
        $count = (clone $query)->where('id', $data['id'])->count();
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
            $table->uint('shipping_id')->default(0);
            $table->string('shipping_name', 30)->default(0);
            $table->decimal('shipping_fee', 8, 2)->default(0);
            $table->string('logistics_number', 30)->default('')->comment('物流单号');
            $table->text('logistics_content')->nullable()->comment('物流信息');
            $table->string('name', 30);
            $table->uint('region_id');
            $table->string('region_name');
            $table->char('tel', 11);
            $table->string('address');
            $table->string('best_time')->default('');
            $table->timestamps();
        })->append(DeliveryGoodsModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('delivery_id');
            $table->uint('order_goods_id');
            $table->uint('goods_id');
            $table->uint('product_id')->default(0);
            $table->string('name', 100)->comment('商品名');
            $table->string('thumb');
            $table->string('series_number', 100);
            $table->uint('amount')->default(1);
            $table->string('type_remark')->default('')->comment('商品类型备注信息');
        });
    }

    /**
     * 创建文章
     */
    public function createArticle(): void {
        $this->append(ArticleModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('cat_id');
            $table->string('title', 100)->comment('文章名');
            $table->string('keywords', 200)->default('')->comment('关键字');
            $table->string('thumb', 200)->default('')->comment('缩略图');
            $table->string('description', 200)->default('')->comment('关键字');
            $table->string('brief', 200)->default('')->comment('简介');
            $table->string('url', 200)->default('')->comment('链接');
            $table->string('file', 200)->default('')->comment('下载内容');
            $table->text('content')->comment('内容');
            $table->timestamps();
        })->append(ArticleCategoryModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 100)->comment('文章分类名');
            $table->string('keywords', 200)->default('')->comment('关键字');
            $table->string('description', 200)->default('')->comment('关键字');
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
            $table->string('name', 30);
            $table->timestamps();
        })->append(AttributeModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->uint('group_id');
            $table->uint('type', 1)->default(0);
            $table->uint('search_type', 1)->default(0);
            $table->uint('input_type', 1)->default(0);
            $table->string('default_value')->default('');
            $table->uint('position', 3)->default(99);
        })->append(GoodsAttributeModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id')->default(0);
            $table->uint('attribute_id');
            $table->string('value');
            $table->decimal('price', 10, 2)->default(0)->comment('附加服务，多选不算在');
        })->append(ProductModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('market_price', 10, 2)->default(0);
            $table->uint('stock')->default(1);
            $table->float('weight', 8, 3)->default(0);
            $table->string('series_number', 50)->default('');
            $table->string('attributes', 100)->default('');
        });
    }

    /**
     * 创建物流
     */
    public function createShipping(): void {
        $this->append(ShippingModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('code', 30);
            $table->uint('method', 2)->default(0)->comment('计费方式');
            $table->string('icon', 100)->default('');
            $table->string('description')->default('');
            $table->uint('position', 2)->default(50);
            $table->timestamps();
        })->append(ShippingGroupModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('shipping_id');
            $table->bool('is_all')->default(0);
            $table->float('first_step', 10, 3)->default(0);
            $table->decimal('first_fee', 10, 2)->default(0);
            $table->float('additional', 10, 3)->default(0);
            $table->decimal('additional_fee', 10, 2)->default(0);
            $table->float('free_step', 10, 3)->default(0);
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
            $table->string('title');
            $table->string('content');
            $table->uint('rank', 2)->default(10);
            $table->timestamps();
        })->append(CommentImageModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('comment_id');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * 创建商品
     */
    public function createGoods(): void {
        $this->append(CategoryModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 100)->comment('分类名');
            $table->string('keywords', 200)->comment('关键字');
            $table->string('description', 200)->comment('关键字');
            $table->string('icon', 200);
            $table->string('banner', 200);
            $table->string('app_banner', 200);
            $table->uint('parent_id')->default(0);
            $table->uint('position', 3)->default(99);
        })->append(CollectModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('goods_id');
            $table->timestamp('created_at');
        })->append(BrandModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 100)->comment('分类名');
            $table->string('keywords', 200)->comment('关键字');
            $table->string('description', 200)->comment('关键字');
            $table->string('logo', 200)->comment('LOGO');
            $table->string('app_logo', 200)->comment('LOGO');
            $table->string('url', 200)->comment('官网');
        })->append(GoodsModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('cat_id');
            $table->column('brand_id')->int();
            $table->string('name', 100)->comment('商品名');
            $table->string('series_number', 100);
            $table->string('keywords', 200)->comment('关键字');
            $table->string('thumb', 200)->comment('缩略图');
            $table->string('picture', 200)->comment('主图');
            $table->string('description', 200)->comment('关键字');
            $table->string('brief', 200)->comment('简介');
            $table->text('content')->nullable()->comment('内容');
            $table->decimal('price', 8, 2)->default(0)->comment('销售价');
            $table->decimal('market_price', 8, 2)->default(0)->comment('原价/市场价');
            $table->decimal('cost_price', 8, 2)->default(0)->comment('成本价');
            $table->uint('stock')->default(1);
            $table->uint('attribute_group_id')->default(0);
            $table->float('weight', 8, 3)->default(0);
            $table->uint('shipping_id')->default(0)->comment('配送方式');
            $table->uint('sales')->default(0)->comment('销量');
            $table->bool('is_best')->default(0);
            $table->bool('is_hot')->default(0);
            $table->bool('is_new')->default(0);
            $table->uint('status', 2)->default(10);
            $table->string('admin_note')->default('')->comment('管理员备注，只后台显示');
            $table->uint('type', 1)->default(0)->comment('商品类型');
            $table->uint('position', 1)->default(99)->comment('排序');
            $table->uint('dynamic_position', 1)
                ->default(0)->comment('动态排序');
            $table->softDeletes();
            $table->timestamps();
        })->append(GoodsMetaModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->string('name', 50);
            $table->text('content');
        })->append(GoodsCardModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->string('card_no');
            $table->string('card_pwd');
            $table->uint('order_id')->default(0);
        })->append(CartModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('type', 1)->default(0);
            $table->uint('user_id');
            $table->uint('goods_id');
            $table->uint('product_id')->default(0);
            $table->uint('amount')->default(1);
            $table->decimal('price', 8, 2);
            $table->bool('is_checked')->default(0)->comment('是否选中');
            $table->uint('selected_activity')->default(0)->comment('选择的活动');
            $table->string('attribute_id')->default('')->comment('选择的属性');
            $table->string('attribute_value')->default('')->comment('选择的属性值');
            $table->timestamp('expired_at')->comment('过期时间');
        })->append(GoodsIssueModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->string('question');
            $table->string('answer')->default('');
        })->append(GoodsGalleryModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('goods_id');
            $table->uint('type', 1)->default(0)->comment('文件类型，图片或视频');
            $table->string('thumb');
            $table->string('file');
        });
    }

    protected function createWarehouse(): void {
        $this->append(WarehouseModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('tel', 30);
            $table->string('link_user', 30)->default('');
            $table->string('address')->default('');
            $table->string('longitude', 50)->comment('经度');
            $table->string('latitude', 50)->comment('纬度');
            $table->string('remark')->default('');
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
            $table->string('remark')->default('');
            $table->timestamp('created_at');
        });
    }

    protected function createAd(): void {
        $this->append(AdModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->uint('position_id');
            $table->uint('type', 1)->default(1);
            $table->string('url');
            $table->string('content');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        })->append(AdPositionModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('width', 20);
            $table->string('height', 20);
            $table->string('template')->default('');
            $table->timestamps();
        });
    }

    protected function createActivity() {
        $this->append(ActivityModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('thumb', 200)->default('');
            $table->string('description')->default('');
            $table->uint('type', 2)->default(ActivityModel::TYPE_AUCTION);
            $table->uint('scope_type', 1)->default(0)->comment('商品范围类型');
            $table->text('scope')->nullable()->comment('商品范围值');
            $table->text('configure')->nullable()->comment('其他配置信息');
            $table->bool('status')->default(ActivityModel::STATUS_NONE)->comment('开启关闭');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        })->append(ActivityTimeModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('title', 40);
            $table->time('start_at');
            $table->time('end_at');
        })->append(SeckillGoodsModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('act_id');
            $table->uint('time_id');
            $table->uint('goods_id')->unsigned();
            $table->decimal('price', 8, 2)->default(0);
            $table->uint('amount', 5)->default(0);
            $table->uint('every_amount', 2)->default(0);
        })->append(AuctionLogModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('act_id');
            $table->uint('user_id');
            $table->decimal('bid', 8, 2)->default(0)
                ->comment('出价');
            $table->uint('amount')->default(1)->comment('出价数量');
            $table->uint('status', 1)->default(AuctionLogModel::STATUS_NONE);
            $table->timestamp('created_at');
        })->append(PresaleLogModel::tableName(), function (Table $table) {
            $table->comment('预售记录');
            $table->id();
            $table->uint('act_id');
            $table->uint('user_id');
            $table->uint('order_id');
            $table->uint('order_goods_id');
            $table->decimal('order_amount', 8, 2)
                ->default(0)->comment('预售总价');
            $table->decimal('deposit', 8, 2)
                ->default(0)->comment('预售定金');
            $table->decimal('final_payment', 8, 2)
                ->default(0)->comment('预售尾款');
            $table->uint('status', 2)->default(0)->comment('判断预售订单处于那个状态');
            $table->timestamp('predetermined_at')->comment('支付定金时间');
            $table->timestamp('final_at')->comment('尾款支付时间');
            $table->timestamp('ship_at')->comment('发货时间');
            $table->timestamps();
        })->append(GroupBuyLogModel::tableName(), function (Table $table) {
            $table->comment('团购记录');
            $table->id();
            $table->uint('act_id');
            $table->uint('user_id');
            $table->uint('order_id');
            $table->uint('order_goods_id');
            $table->decimal('deposit', 8, 2)
                ->default(0)->comment('定金');
            $table->decimal('final_payment', 8, 2)
                ->default(0)->comment('尾款');
            $table->uint('status', 2)->default(0)->comment('判断预售订单处于那个状态');
            $table->timestamp('predetermined_at')->comment('支付定金时间');
            $table->timestamp('final_at')->comment('尾款支付时间');
            $table->timestamps();
        })->append(BargainUserModel::tableName(), function (Table $table) {
            $table->comment('用户参与砍价');
            $table->id();
            $table->uint('act_id');
            $table->uint('user_id');
            $table->uint('goods_id');
            $table->decimal('price', 10, 2)->comment('当前价格');
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(BargainLogModel::tableName(), function (Table $table) {
            $table->comment('用户帮砍记录');
            $table->id();
            $table->uint('bargain_id');
            $table->uint('user_id');
            $table->decimal('amount', 8, 2)->default(0)->comment('砍掉的价格');
            $table->decimal('price', 8, 2)->default(0)
                ->comment('砍掉之后剩余的价格');
            $table->timestamp('created_at');
        })->append(TrialLogModel::tableName(), function (Table $table) {
            $table->comment('用户申请试用记录');
            $table->id();
            $table->uint('act_id');
            $table->uint('user_id');
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(TrialReportModel::tableName(), function (Table $table) {
            $table->comment('用户试用报告');
            $table->id();
            $table->uint('act_id');
            $table->uint('user_id');
            $table->uint('goods_id');
            $table->string('title');
            $table->text('content')->nullable();
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(LotteryLogModel::tableName(), function (Table $table) {
            $table->comment('用户抽奖记录');
            $table->id();
            $table->uint('act_id');
            $table->uint('user_id');
            $table->uint('item_type', 1)->default(0);
            $table->uint('item_id')->default(0);
            $table->uint('amount')->default(0);
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        });
    }

    private function createPay(): void {
        $this->append(PaymentModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('code', 30);
            $table->string('icon', 100)->default('');
            $table->string('description')->default('');
            $table->text('settings')->nullable();
        })->append(PayLogModel::tableName(), function (Table $table) {
            $table->id()->ai(10000001);
            $table->uint('payment_id');
            $table->string('payment_name', 30)->default('');
            $table->uint('type', 1)->default(0);
            $table->uint('user_id');
            $table->string('data')->default('')->comment('可以接受多个订单号');
            $table->uint('status', 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 10)->default('')->comment('货币');
            $table->decimal('currency_money', 10, 2)->default(0)->comment('货币金额');
            $table->string('trade_no', 100)->default('')->comment('外部订单号');
            $table->timestamp('begin_at')->comment('开始时间');
            $table->timestamp('confirm_at')->comment('确认支付时间');
            $table->timestamps();
        });
    }

}