<?php
namespace Module\SMS\Domain\Migrations;

use Module\SMS\Domain\Model\SmsLogModel;
use Module\SMS\Domain\Model\SmsSignatureModel;
use Module\SMS\Domain\Model\SmsTemplateModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateSmsTables extends Migration {

    public function up() {
        $this->append(SmsSignatureModel::tableName(), function (Table $table) {
            $table->comment('短信签名');
            $table->id();
            $table->column('sign_no')->varchar(32)->default('')->comment('外部编号');
            $table->column('name')->varchar(20)->comment('名称');
            $table->column('is_default')->bool()->default(0)->comment('是否为默认值');
        })->append(SmsTemplateModel::tableName(), function (Table $table) {
           $table->comment('短信模板');
           $table->id();
           $table->column('name')->varchar(50)->comment('标题');
           $table->uint('type', 1)->default(0)->comment('短信的类型');
           $table->column('sign_no')->varchar(32)->default('')->comment('外部编号');
           $table->column('content')->varchar()->comment('模板内容');
           $table->uint('signature_id')->default(0)->comment('绑定的签名');
        })->append(SmsLogModel::tableName(), function (Table $table) {
            $table->comment('短信发送记录');
            $table->id();
            $table->uint('signature_id')->default(0)->comment('签名');
            $table->uint('template_id')->default(0)->comment('模板内容');
            $table->column('mobile')->varchar(20)->comment('接受手机号');
            $table->uint('type', 2)->default(0)->comment('短信的类型');
            $table->column('content')->varchar()->default('')->comment('发送的内容');
            $table->uint('status', 2)->default(0)->comment('发送状态');
            $table->column('ip')->varchar(120)->default('')->comment('发送者ip');
            $table->timestamps();
        })->autoUp();
    }
}