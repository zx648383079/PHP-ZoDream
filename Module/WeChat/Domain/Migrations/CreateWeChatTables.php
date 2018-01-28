<?php
namespace Module\WeChat\Domain\Migrations;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\MessageHistoryModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\ReplyModel;
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
        $this->initFansTable();
        $this->initUserTable();
        $this->initMessageHistoryTable();
        $this->initMediaTable();

        Schema::createTable(ReplyModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('event')->varchar(20)->notNull()->comment('时间');
            $table->set('keywords')->varchar(60)->notNull()->comment('关键词');
            $table->set('content')->text()->notNull()->comment('微信返回数据');
            $table->set('type')->varchar(10)->notNull()->comment('素材类型');
            $table->timestamps();
        });

        Schema::createTable(MenuModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('name')->varchar(100)->notNull()->comment('素材ID');
            $table->set('type')->varchar(100)->notNull()->comment('素材类型');
            $table->set('content')->text()->notNull()->comment('微信返回数据');
            $table->set('pages')->text()->notNull()->comment('小程序路径');
            $table->set('parent_id')->int()->defaultVal(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(WeChatModel::tableName());
        Schema::dropTable(ReplyModel::tableName());
        Schema::dropTable(FansModel::tableName());
        Schema::dropTable(UserModel::tableName());
        Schema::dropTable(MessageHistoryModel::tableName());
        Schema::dropTable(MediaModel::tableName());
        Schema::dropTable(MenuModel::tableName());
    }

    /**
     * 公众号表
     */
    public function initWeChatTable() {
        Schema::createTable(WeChatModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
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
     * 粉丝表
     */
    public function initFansTable() {
        Schema::createTable(FansModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
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
        Schema::createTable(UserModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai()->comment('粉丝ID');
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
            $table->set('id')->pk()->ai();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('rid')->int(10)->unsigned()->notNull()->comment('相应规则ID');
            $table->set('kid')->int(10)->unsigned()->notNull()->comment('所属关键字ID');
            $table->set('from')->varchar(50)->notNull()->comment('请求用户ID');
            $table->set('to')->varchar(50)->notNull()->comment('相应用户ID');
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
            $table->set('id')->pk()->ai();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('type')->varchar(10)->notNull()->comment('素材类型');
            $table->set('title')->varchar(200)->comment('素材标题');
            $table->set('content')->text()->comment('素材内容');
            $table->set('parent_id')->int()->comment('图文父id');
            $table->set('media_id')->varchar(100)->notNull()->comment('素材ID');
            $table->set('result')->text()->notNull()->comment('微信返回数据');
            $table->timestamps();
        });
    }
}