<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Migrations;

use Domain\Repositories\LocalizeRepository;
use Module\Auth\Domain\Repositories\RoleRepository;
use Module\TradeTracker\Domain\Entities\ChannelEntity;
use Module\TradeTracker\Domain\Entities\ChannelProductEntity;
use Module\TradeTracker\Domain\Entities\ProductEntity;
use Module\TradeTracker\Domain\Entities\TradeEntity;
use Module\TradeTracker\Domain\Entities\TradeLogEntity;
use Module\TradeTracker\Domain\Entities\UserProductEntity;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Model\Model;
use Zodream\Database\Schema\Table;

class CreateTradeTrackerTables extends Migration {

    public function up(): void {
        $this->append(ProductEntity::tableName(), function (Table $table) {
            $table->comment('商品货品表');
            $table->id();
            $table->uint('parent_id')->default(0);
            foreach (LocalizeRepository::languageAsColumnPrefix() as $lang) {
                $table->string($lang.'name', 100)->nullable(!empty($lang));
            }
            $table->uint('cat_id')->default(0);
            $table->uint('project_id')->default(0);
            $table->string('unique_code', 100)->default('');
            $table->bool('is_sku')->default(1);
            $table->timestamps();
        })->append(ChannelEntity::tableName(), function (Table $table) {
            $table->comment('渠道表');
            $table->id();
            $table->string('short_name', 20)->default('');
            $table->string('name', 40);
            $table->string('site_url')->default('');
            $table->timestamps();
        })->append(ChannelProductEntity::tableName(), function (Table $table) {
            $table->comment('渠道货品表');
            $table->id();
            $table->uint('product_id');
            $table->uint('channel_id');
            $table->string('platform_no', 40)->default('');
            $table->string('extra_meta')->default('');
            $table->timestamps();
        })->append(TradeEntity::tableName(), function (Table $table) {
            $table->comment('交易价格（按天取出售最低价格求购最高价）');
            $table->id();
            $table->uint('product_id');
            $table->uint('channel_id');
            $table->bool('type')->default(0)->comment('0 出售, 1 求购');
            $table->decimal('price', 12, 2);
            $table->uint('order_count')->default(0);
            $table->timestamp(Model::CREATED_AT);
        })->append(TradeLogEntity::tableName(), function (Table $table) {
            $table->comment('价格变动记录（只保留一天）');
            $table->id();
            $table->uint('product_id');
            $table->uint('channel_id');
            $table->bool('type')->default(0)->comment('0 出售, 1 求购');
            $table->decimal('price', 12, 2);
            $table->timestamp(Model::CREATED_AT);
        })->append(UserProductEntity::tableName(), function (Table $table) {
            $table->comment('已购商品');
            $table->id();
            $table->uint('user_id');
            $table->uint('product_id');
            $table->uint('channel_id');
            $table->decimal('price', 12, 2);
            $table->decimal('sell_price', 12, 2)->default(0);
            $table->uint('sell_channel_id')->default(0);
            $table->uint('status')->default(0);
            $table->timestamps();
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'tracker_manage' => '交易跟踪管理'
        ]);
    }
}
