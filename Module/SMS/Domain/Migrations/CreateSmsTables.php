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
            $table->set('sign_no')->varchar(32)->defaultVal('')->comment('外部编号');
            $table->set('name')->varchar(20)->notNull()->comment('名称');
            $table->set('is_default')->bool()->defaultVal(0)->comment('是否为默认值');
        })->append(SmsTemplateModel::tableName(), function (Table $table) {
           $table->setComment('短信模板');
           $table->set('id')->pk(true);
           $table->set('name')->varchar(50)->notNull()->comment('标题');
           $table->set('type')->tinyint(1)->defaultVal(0)->comment('短信的类型');
           $table->set('sign_no')->varchar(32)->defaultVal('')->comment('外部编号');
           $table->set('content')->varchar()->notNull()->comment('模板内容');
           $table->set('signature_id')->int()->defaultVal(0)->comment('绑定的签名');
        })->append(SmsLogModel::tableName(), function (Table $table) {
            $table->setComment('短信发送记录');
            $table->set('id')->pk(true);
            $table->set('signature_id')->int()->defaultVal(0)->comment('签名');
            $table->set('template_id')->int()->defaultVal(0)->comment('模板内容');
            $table->set('mobile')->varchar(20)->comment('接受手机号');
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('短信的类型');
            $table->set('content')->varchar()->comment('发送的内容');
            $table->set('status')->tinyint(1)->notNull()->defaultVal(0)->comment('发送状态');
            $table->set('ip')->varchar(120)->comment('发送者ip');
            $table->timestamps();
        })->autoUp();
    }
}