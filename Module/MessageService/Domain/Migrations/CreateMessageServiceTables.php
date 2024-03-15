<?php
declare(strict_types=1);
namespace Module\MessageService\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\MessageService\Domain\Entities\LogEntity;
use Module\MessageService\Domain\Entities\TemplateEntity;
use Module\MessageService\Domain\Repositories\MessageProtocol;
use Module\MessageService\Domain\Repositories\MessageRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateMessageServiceTables extends Migration {

    public function up(): void {
        $this
//            ->append(SmsSignatureModel::tableName(), function (Table $table) {
//            $table->comment('短信签名');
//            $table->id();
//            $table->string('sign_no', 32)->default('')->comment('外部编号');
//            $table->string('name', 20)->comment('名称');
//            $table->bool('is_default')->default(0)->comment('是否为默认值');
//        })
        ->append(TemplateEntity::tableName(), function (Table $table) {
           $table->comment('消息模板');
           $table->id();
           $table->string('title', 100)->comment('标题');
           $table->string('name', 20)->comment('调用代码');
           $table->uint('type', 1)->default(MessageProtocol::TYPE_TEXT)
               ->comment('模板的类型');
           $table->string('data')->comment('模板字段');
           $table->text('content')->comment('模板内容');
           $table->string('target_no', 32)->default('')->comment('外部编号');
           $table->timestamps();
        })->append(LogEntity::tableName(), function (Table $table) {
            $table->comment('短信发送记录');
            $table->id();
            $table->uint('template_id')->default(0)->comment('模板id');
            $table->uint('target_type', 1)->comment('接受者类型');
            $table->string('target', 100)->comment('接受者，手机号/邮箱');
            $table->string('template_name', 20)->default('')->comment('调用代码');
            $table->uint('type', 1)->default(MessageProtocol::TYPE_TEXT)
                ->comment('内容的类型');
            $table->string('title')->comment('发送的标题');
            $table->text('content')->comment('发送的内容');
            $table->string('code', 50)->default('')->comment('发送的验证码');
            $table->uint('status', 1)->default(0)->comment('发送状态');
            $table->string('message')->default('')->comment('发送结果，成功为消息id,否则为错误信息');
            $table->string('ip', 120)->default('')->comment('发送者ip');
            $table->timestamps();
        })->autoUp();
    }

    public function seed(): void {
        MessageRepository::insertIf(MessageProtocol::EVENT_LOGIN_CODE,
            '登录验证码', '登录验证码{code}');
        MessageRepository::insertIf(MessageProtocol::EVENT_REGISTER_CODE,
            '注册验证码', '注册验证码{code}');
        MessageRepository::insertIf(MessageProtocol::EVENT_REGISTER_EMAIL_CODE,
            '验证你的Email', '<a href="{url}">确认你的Email</a><br/>或复制链接在浏览器中打开：<br/>{url}', MessageProtocol::TYPE_HTML);
        MessageRepository::insertIf(MessageProtocol::EVENT_FIND_CODE,
            '重置密码验证码', '重置密码验证码{code}');
    }
}