<?php
namespace Module\SMS\Domain\Migrations;

use Module\SMS\Domain\Model\SmsLogModel;
use Module\SMS\Domain\Model\SmsSignatureModel;
use Module\SMS\Domain\Model\SmsTemplateModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateSmsTables extends Migration {

    public function up() {
        $this->append(SmsSignatureModel::tableName(), function (Table $table) {
            $table->setComment('短信签名');
            $table->set('id')->pk(true);
            $table->set('sign')->varchar(60)->comment('编号');
            $table->set('name')->varchar(20)->notNull()->comment('名称');
        })->append(SmsTemplateModel::tableName(), function (Table $table) {
           $table->setComment('短信模板');
           $table->set('id')->pk(true);
           $table->set('sign')->varchar('60')->comment('编号');
           $table->set('content')->varchar('255')->notNull()->comment('模板内容');
        })->append(SmsLogModel::tableName(), function (Table $table) {
            $table->setComment('短信发送记录');
            $table->set('id')->pk(true);
            $table->set('signature_id')->int()->defaultVal(0)->comment('签名');
            $table->set('template_id')->int()->defaultVal(0)->comment('模板内容');
            $table->set('mobile')->varchar(20)->comment('接受手机号');
            $table->set('content')->varchar('255')->comment('发送的内容');
            $table->set('status')->tinyint(1)->notNull()->defaultVal(0)->comment('发送状态');
            $table->set('ip')->varchar('120')->comment('发送者ip');
            $table->timestamps();
        })->autoUp();
    }
}