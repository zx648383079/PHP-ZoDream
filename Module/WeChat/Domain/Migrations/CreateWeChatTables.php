<?php
namespace Module\WeChat\Domain\Migrations;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\MessageHistoryModel;
use Module\WeChat\Domain\Model\ModuleModel;
use Module\WeChat\Domain\Model\MpUserModel;
use Module\WeChat\Domain\Model\ReplyRuleKeywordModel;
use Module\WeChat\Domain\Model\ReplyRuleModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateWeChatTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->initWechatTable();
        $this->initModuleTable();
        $this->initReplyRuleTable();
        $this->initFansTable();
        $this->initUserTable();
        $this->initMessageHistoryTable();
        $this->initMediaTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(WeChatModel::tableName());
        Schema::dropTable(ReplyRuleModel::tableName());
        Schema::dropTable(ReplyRuleKeywordModel::tableName());
        Schema::dropTable(FansModel::tableName());
        Schema::dropTable(MpUserModel::tableName());
        Schema::dropTable(MessageHistoryModel::tableName());
        Schema::dropTable(MediaModel::tableName());
    }

    /**
     * 公众号表
     */
    public function initWeChatTable() {
        Schema::createTable(WeChatModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('name')->varchar(40)->notNull()->comment('公众号名称');
            $table->set('token')->varchar(32)->notNull()->comment('微信服务访问验证token');
            $table->set('access_token')->varchar()->notNull()->comment('访问微信服务验证token');
            $table->set('account')->varchar(30)->notNull()->comment('微信号');
            $table->set('original')->varchar(40)->notNull()->comment('原始ID');
            $table->set('type')->bool()->unsigned()->notNull()->comment('公众号类型');
            $table->set('appid')->varchar(50)->notNull()->comment('公众号的AppID');
            $table->set('secret')->varchar(50)->notNull()->comment('公众号的AppSecret');
            $table->set('aes_key')->varchar(43)->notNull()->comment('消息加密秘钥EncodingAesKey');
            $table->set('avatar')->varchar()->notNull()->comment('头像地址');
            $table->set('qrcode')->varchar()->notNull()->comment('二维码地址');
            $table->set('address')->varchar()->notNull()->comment('所在地址');
            $table->set('description')->varchar()->notNull()->comment('公众号简介');
            $table->set('username')->varchar(40)->notNull()->comment('微信官网登录名');
            $table->set('status')->bool()->notNull()->comment('状态');
            $table->set('password')->varchar(32)->notNull()->comment('微信官网登录密码');
            $table->timestamps();
        });
    }
    /**
     * 扩展模块表
     */
    public function initModuleTable() {
        Schema::createTable(ModuleModel::tableName(), function(Table $table) {
            $table->set('id')->varchar(20)->notNull()->comment('模块ID');
            $table->set('name')->varchar(50)->notNull()->comment('模块名称');
            $table->set('type')->varchar(20)->notNull()->comment('模块类型');
            $table->set('category')->varchar(20)->notNull()->comment('模块类型');
            $table->set('version')->varchar(10)->notNull()->comment('模块版本');
            $table->set('ability')->varchar(100)->notNull()->comment('模块功能简述');
            $table->set('description')->text()->notNull()->comment('模块详细描述');
            $table->set('author')->varchar(50)->notNull()->comment('模块作者');
            $table->set('site')->varchar()->notNull()->comment('模块详情地址');
            $table->set('admin')->bool()->notNull()->comment('是否有后台界面');
            $table->set('migration')->bool()->notNull()->comment('是否有迁移数据');
            $table->set('reply_rule')->bool()->notNull()->comment('是否启用回复规则');
            $table->timestamps();
        });
    }
    /**
     * 回复规则表
     */
    public function initReplyRuleTable() {
        Schema::createTable(ReplyRuleModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('name')->varchar(40)->notNull()->comment('规则名称');
            $table->set('mid')->varchar(20)->notNull()->comment('处理的插件模块');
            $table->set('processor')->varchar(40)->notNull()->comment('处理类');
            $table->set('status')->bool()->defaultVal(0)->notNull()->comment('状态');
            $table->set('priority')->int(3)->unsigned()->defaultVal(0)->notNull()->comment('优先级');
            $table->timestamps();
        });
        // 回复规则关键字表
        Schema::createTable(ReplyRuleKeywordModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('rid')->int(10)->unsigned()->notNull()->comment('所属规则ID');
            $table->set('keyword')->varchar()->notNull()->comment('微规则关键字');
            $table->set('type')->varchar(20)->notNull()->comment('关键字类型');
            $table->set('priority')->tinyint(3)->unsigned()->notNull()->comment('优先级');
            $table->set('start_at')->int(10)->unsigned()->defaultVal(0)->notNull()->comment('开始时间');
            $table->set('end_at')->int(10)->unsigned()->defaultVal(0)->notNull()->comment('结束时间');
            $table->timestamps();
        });
    }
    /**
     * 粉丝表
     */
    public function initFansTable() {
        Schema::createTable(FansModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('openid')->varchar(50)->notNull()->comment('微信ID');
            $table->set('status')->bool()->notNull()->comment('关注状态');
            $table->timestamps();
        });
    }
    /**
     * 粉丝用户表
     */
    public function initUserTable() {
        // 公众号粉丝详情表
        Schema::createTable(MpUserModel::tableName(), function(Table $table) {
            $table->set('id')->int(10)->unsigned()->notNull()->comment('粉丝ID');
            $table->set('nickname')->varchar(20)->notNull()->comment('昵称');
            $table->set('sex')->bool()->unsigned()->notNull()->defaultVal(0)->comment('性别');
            $table->set('city')->varchar(40)->notNull()->comment('所在城市');
            $table->set('country')->varchar(40)->notNull()->comment('所在国家');
            $table->set('province')->varchar(40)->notNull()->comment('所在省');
            $table->set('language')->varchar(40)->notNull()->comment('用户语言');
            $table->set('avatar')->varchar()->notNull()->comment('用户头像');
            $table->set('subscribe_time')->int(10)->unsigned()->defaultVal(0)->null();
            $table->set('union_id')->varchar(30)->notNull()->comment('微信ID');
            $table->set('remark')->varchar()->notNull()->comment('备注');
            $table->set('group_id')->smallInt(5)->notNull();
            $table->timestamp('updated_at');
        });
    }
    /**
     * 消息记录表
     */
    public function initMessageHistoryTable() {
        Schema::createTable(MessageHistoryModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('rid')->int(10)->unsigned()->notNull()->comment('相应规则ID');
            $table->set('kid')->int(10)->unsigned()->notNull()->comment('所属关键字ID');
            $table->set('from')->varchar(50)->notNull()->comment('请求用户ID');
            $table->set('to')->varchar(50)->notNull()->comment('相应用户ID');
            $table->set('module')->varchar(20)->notNull()->comment('处理模块');
            $table->set('message')->text()->notNull()->comment('消息体内容');
            $table->set('type')->varchar(10)->notNull()->comment('发送类型');
            $table->timestamp('created_at');
        });
    }
    /**
     * 素材表
     */
    public function initMediaTable() {
        Schema::createTable(MediaModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('mediaId')->varchar(100)->notNull()->comment('素材ID');
            $table->set('filename')->varchar(100)->notNull()->comment('文件名');
            $table->set('result')->text()->notNull()->comment('微信返回数据');
            $table->set('type')->varchar(10)->notNull()->comment('素材类型');
            $table->set('material')->varchar(20)->notNull()->comment('素材类别');
            $table->timestamps();
        });
    }
}