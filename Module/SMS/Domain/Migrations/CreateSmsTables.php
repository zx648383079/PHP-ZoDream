<?php
namespace Module\SMS\Doamin\Migrations;

use Module\SMS\Doamin\Model\SmsLogModel;
use Module\SMS\Doamin\Model\SmsSignatureModel;
use Module\SMS\Doamin\Model\SmsTemplateModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateSmsTables extends Migration {

    public function up() {
        Schema::createTable(SmsSignatureModel::tableName(), function (Table $table) {
            $table->setComment('短信签名');
            $table->set('id')->pk()->ai();
            $table->set('sign')->varchar(60)->comment('编号');
            $table->set('name')->varchar(20)->notNull()->comment('名称');
        });
        Schema::createTable(SmsTemplateModel::tableName(), function (Table $table) {
           $table->setComment('短信模板');
           $table->set('id')->pk()->ai();
           $table->set('sign')->varchar('60')->comment('编号');
           $table->set('content')->varchar('255')->notNull()->comment('模板内容');
        });
        Schema::createTable(SmsLogModel::tableName(), function (Table $table) {
            $table->setComment('短信发送记录');
            $table->set('id')->pk()->ai();
            $table->set('signature_id')->int()->comment('签名');
            $table->set('template_id')->int()->comment('模板内容');
            $table->set('mobile')->varchar(20)->comment('接受手机号');
            $table->set('content')->varchar('255')->comment('发送的内容');
            $table->set('status')->tinyint(1)->notNull()->defaultVal(0)->comment('发送状态');
            $table->set('ip')->varchar('120')->comment('发送者ip');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropTable(SmsSignatureModel::tableName());
        Schema::dropTable(SmsTemplateModel::tableName());
        Schema::dropTable(SmsLogModel::tableName());
    }
}