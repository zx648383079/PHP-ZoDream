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
            $table->setComment('跑腿订单');
            $table->set('id')->pk(true);
            $table->set('provider_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('service_id')->int()->notNull();
            $table->set('amount')->smallInt(4)->defaultVal(1)->comment('购买服务的数量');
            $table->set('remark')->text()->notNull()->comment('服务内容');
            $table->set('order_amount')->decimal(8, 2)->defaultVal(0)->comment('订单金额');
            $table->set('waiter_id')->int()->defaultVal(0)->comment('跑腿人');
            $table->set('status')->tinyint(1)->defaultVal(OrderModel::STATUS_UN_PAY);
            $table->set('service_score')->tinyint(1)
                ->defaultVal(10)->comment('服务评分');
            $table->set('waiter_score')->tinyint(1)
                ->defaultVal(10)->comment('服务员评分');
            $table->timestamp('pay_at')->comment('支付时间');
            $table->timestamp('taking_at')->comment('接单时间');
            $table->timestamp('taken_at')->comment('完成接单任务时间');
            $table->timestamp('finish_at')->comment('完成时间');
            $table->timestamps();
        })->append(OrderLogModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('order_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('status')->tinyint(1)->defaultVal(1);
            $table->set('remark')->varchar()->defaultVal('');
            $table->timestamp('created_at');
        })->append(ServiceModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull()->comment('服务名');
            $table->set('cat_id')->int()->defaultVal(0)->comment('服务分类');
            $table->set('thumb')->varchar(200)->comment('缩略图');
            $table->set('brief')->varchar()->defaultVal('')->comment('说明');
            $table->set('price')->decimal(8, 2)->defaultVal(0);
            $table->set('content')->text()->notNull()->comment('内容');
            $table->set('form')->text()->comment('表单设置');
            $table->set('status')->tinyint(1)->defaultVal(0)
                ->comment('服务状态');
            $table->timestamps();
        })->append(CategoryModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('name')->varchar(100)->notNull()->comment('分类名');
            $table->set('icon')->varchar(200)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
        })->append(CategoryProviderModel::tableName(), function (Table $table) {
            $table->set('user_id')->int()->notNull();
            $table->set('cat_id')->int()->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0);
        })->append(ProviderModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull()->comment('名称');
            $table->set('logo')->varchar()->defaultVal('')->comment('LOGO');
            $table->set('tel')->varchar(30)->notNull()->comment('联系方式');
            $table->set('address')->varchar()->notNull()->comment('联系地址');
            $table->set('longitude')->varchar(50)->comment('经度');
            $table->set('latitude')->varchar(50)->comment('纬度');
            $table->set('overall_rating')
                ->tinyint(1)->defaultVal(5)
                ->comment('综合评分');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamps();
        })->append(WaiterModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull()->comment('名称');
            $table->set('tel')->varchar(30)->notNull()->comment('联系方式');
            $table->set('address')->varchar()->notNull()->comment('联系地址');
            $table->set('longitude')->varchar(50)->comment('经度');
            $table->set('latitude')->varchar(50)->comment('纬度');
            $table->set('max_service')->smallInt(4)
                ->defaultVal(1)->comment('每次同时服务最大数量');
            $table->set('overall_rating')
                ->tinyint(1)->defaultVal(5)
                ->comment('综合评分');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamps();
        })->append(ServiceRegionModel::tableName(), function (Table $table) {
            $table->set('service_id')->int()->notNull();
            $table->set('region_id')->int()->notNull();
        })->append(ServiceWaiterModel::tableName(), function (Table $table) {
            $table->set('service_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0);
        })->autoUp();
    }
}
