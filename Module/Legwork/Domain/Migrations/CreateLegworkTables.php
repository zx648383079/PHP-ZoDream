<?php
namespace Module\Legwork\Domain\Migrations;

use Module\Legwork\Domain\Model\OrderLogModel;
use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Model\ServiceModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateLegworkTables extends Migration {

    public function up() {
        $this->append(OrderModel::tableName(), function (Table $table) {
            $table->setComment('跑腿订单');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('service_id')->int()->notNull();
            $table->set('remark')->text()->notNull()->comment('服务内容');
            $table->set('order_amount')->decimal(8, 2)->defaultVal(0)->comment('公用回传参数，如果请求时传递了该参数，则返回给商户时会回传该参数。支付宝只会在同步返回（包括跳转回商户网站）和异步通知时将该参数原样返回');
            $table->set('runner')->int()->defaultVal(0)->comment('跑腿人');
            $table->set('status')->tinyint(1)->defaultVal(OrderModel::STATUS_UN_PAY);
            $table->set('service_rank')->tinyint(1)->defaultVal(10)->comment('服务评价');
            $table->timestamp('pay_at')->comment('支付时间');
            $table->timestamp('taking_at')->comment('接单时间');
            $table->timestamp('taken_at')->comment('完成接单任务时间');
            $table->timestamp('finish_at')->comment('完成时间');
            $table->timestamps();
        })->append(OrderLogModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('order_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('status')->int()->defaultVal(1);
            $table->set('remark')->varchar()->defaultVal('');
            $table->timestamp('created_at');
        })->append(ServiceModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('name')->varchar(100)->notNull()->comment('服务名');
            $table->set('cat_id')->int()->defaultVal(0)->comment('服务分类');
            $table->set('thumb')->varchar(200)->comment('缩略图');
            $table->set('brief')->varchar()->defaultVal('')->comment('说明');
            $table->set('price')->decimal(8, 2)->defaultVal(0);
            $table->set('content')->text()->notNull()->comment('内容');
            $table->set('form')->text()->comment('表单设置');
            $table->timestamps();
        })->autoUp();
    }
}
