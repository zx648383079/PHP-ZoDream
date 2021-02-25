<?php
namespace Module\Legwork\Domain\Migrations;

use Module\Legwork\Domain\Model\CategoryModel;
use Module\Legwork\Domain\Model\CategoryProviderModel;
use Module\Legwork\Domain\Model\OrderLogModel;
use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Model\ProviderModel;
use Module\Legwork\Domain\Model\ServiceModel;
use Module\Legwork\Domain\Model\ServiceRegionModel;
use Module\Legwork\Domain\Model\ServiceWaiterModel;
use Module\Legwork\Domain\Model\WaiterModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateLegworkTables extends Migration {

    public function up() {
        $this->append(OrderModel::tableName(), function (Table $table) {
            $table->comment('跑腿订单');
            $table->id();
            $table->uint('provider_id');
            $table->uint('user_id');
            $table->uint('service_id');
            $table->uint('amount', 4)->default(1)->comment('购买服务的数量');
            $table->column('remark')->text()->comment('服务内容');
            $table->column('order_amount')->decimal(8, 2)->default(0)->comment('订单金额');
            $table->uint('waiter_id')->default(0)->comment('跑腿人');
            $table->uint('status', 2)->default(OrderModel::STATUS_UN_PAY);
            $table->uint('service_score', 2)
                ->default(10)->comment('服务评分');
            $table->uint('waiter_score', 2)
                ->default(10)->comment('服务员评分');
            $table->timestamp('pay_at')->comment('支付时间');
            $table->timestamp('taking_at')->comment('接单时间');
            $table->timestamp('taken_at')->comment('完成接单任务时间');
            $table->timestamp('finish_at')->comment('完成时间');
            $table->timestamps();
        })->append(OrderLogModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('order_id');
            $table->uint('user_id');
            $table->uint('status', 2)->default(1);
            $table->column('remark')->varchar()->default('');
            $table->timestamp('created_at');
        })->append(ServiceModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(100)->comment('服务名');
            $table->uint('cat_id')->default(0)->comment('服务分类');
            $table->column('thumb')->varchar(200)->comment('缩略图');
            $table->column('brief')->varchar()->default('')->comment('说明');
            $table->column('price')->decimal(8, 2)->default(0);
            $table->column('content')->text()->comment('内容');
            $table->column('form')->text()->nullable()->comment('表单设置');
            $table->uint('status', 2)->default(0)
                ->comment('服务状态');
            $table->timestamps();
        })->append(CategoryModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(100)->comment('分类名');
            $table->column('icon')->varchar(200)->default('');
            $table->column('description')->varchar()->default('');
        })->append(CategoryProviderModel::tableName(), function (Table $table) {
            $table->uint('user_id');
            $table->uint('cat_id');
            $table->uint('status', 2)->default(0);
        })->append(ProviderModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(100)->comment('名称');
            $table->column('logo')->varchar()->default('')->comment('LOGO');
            $table->column('tel')->varchar(30)->comment('联系方式');
            $table->column('address')->varchar()->comment('联系地址');
            $table->column('longitude')->varchar(50)->default('')->comment('经度');
            $table->column('latitude')->varchar(50)->default('')->comment('纬度');
            $table->uint('overall_rating', 2)->default(5)
                ->comment('综合评分');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(WaiterModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(100)->comment('名称');
            $table->column('tel')->varchar(30)->comment('联系方式');
            $table->column('address')->varchar()->comment('联系地址');
            $table->column('longitude')->varchar(50)->comment('经度');
            $table->column('latitude')->varchar(50)->comment('纬度');
            $table->uint('max_service', 4)
                ->default(1)->comment('每次同时服务最大数量');
            $table->uint('overall_rating', 2)->default(5)
                ->comment('综合评分');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(ServiceRegionModel::tableName(), function (Table $table) {
            $table->uint('service_id');
            $table->uint('region_id');
        })->append(ServiceWaiterModel::tableName(), function (Table $table) {
            $table->uint('service_id');
            $table->uint('user_id');
            $table->uint('status', 2)->default(0);
        })->autoUp();
    }
}
