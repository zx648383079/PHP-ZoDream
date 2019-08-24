<?php
namespace Module\WeChat\Domain\Migrations;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\MediaTemplateModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\MessageHistoryModel;
use Module\WeChat\Domain\Model\TemplateModel;
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
            $table->setComment('微信回复');
            $table->set('id')->pk()->ai();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('event')->varchar(20)->notNull()->comment('事件');
            $table->set('keywords')->varchar(60)->comment('关键词');
            $table->set('match')->bool()->notNull()->defaultVal(0)->comment('关键词匹配模式');
            $table->set('content')->text()->notNull()->comment('微信返回数据');
            $table->set('type')->varchar(10)->notNull()->comment('素材类型');
            $table->timestamps();
        });

        Schema::createTable(MenuModel::tableName(), function(Table $table) {
            $table->setComment('微信菜单');
            $table->set('id')->pk()->ai();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('name')->varchar(100)->notNull()->comment('菜单名称');
            $table->set('type')->varchar(100)->notNull()->comment('菜单类型');
            $table->set('content')->text()->notNull()->comment('菜单数据');
            $table->set('pages')->varchar(200)->comment('小程序路径');
            $table->set('parent_id')->int()->defaultVal(0);
            $table->timestamps();
        });

        Schema::createTable(MediaTemplateModel::tableName(), function(Table $table) {
            $table->setComment('微信图文模板');
            $table->set('id')->pk()->ai();
            $table->set('type')->tinyint(3)->defaultVal(0)->comment('类型：素材、节日、行业');
            $table->set('category')->int()->defaultVal(0)->comment('详细分类');
            $table->set('name')->varchar(100)->notNull()->comment('模板标题');
            $table->set('content')->text()->notNull()->comment('模板内容');
            $table->timestamps();
        });
        Schema::createTable(TemplateModel::tableName(), function(Table $table) {
            $table->setComment('微信模板消息模板');
            $table->set('id')->pk()->ai();
            $table->set('wid')->int(10)->unsigned()->notNull()->comment('所属微信公众号ID');
            $table->set('template_id')->varchar(64)->notNull()->comment('模板id');
            $table->set('title')->varchar(100)->notNull()->comment('标题');
            $table->set('content')->varchar(255)->notNull()->comment('内容');
            $table->set('example')->varchar(255)->notNull()->comment('示例');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable([
            WeChatModel::tableName(),
            ReplyModel::tableName(),
            FansModel::tableName(),
            UserModel::tableName(),
            MessageHistoryModel::tableName(),
            MediaModel::tableName(),
            MenuModel::tableName(),
            TemplateModel::tableName(),
            MediaTemplateModel::tableName()
        ]);
    }

    /**
     * 公众号表
     */
    public function initWeChatTable() {
        Schema::createTable(WeChatModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull()->comment('公众号名称');
            $table->set('token')->varchar(32)->notNull()->comment('微信服务访问验证token');
            $table->set('access_token')->varchar()->comment('访问微信服务验证token');
            $table->set('account')->varchar(30)->notNull()->comment('微信号');
            $table->set('original')->varchar(40)->notNull()->comment('原始ID');
            $table->set('type')->tinyint(1)->unsigned()->notNull()->defaultVal(0)->comment('公众号类型');
            $table->set('appid')->varchar(50)->notNull()->comment('公众号的AppID');
            $table->set('secret')->varchar(50)->notNull()->comment('公众号的AppSecret');
            $table->set('aes_key')->varchar(43)->comment('消息加密秘钥EncodingAesKey');
            $table->set('avatar')->varchar()->comment('头像地址');
            $table->set('qrcode')->varchar()->comment('二维码地址');
            $table->set('address')->varchar()->comment('所在地址');
            $table->set('description')->varchar()->comment('公众号简介');
            $table->set('username')->varchar(40)->comment('微信官网登录名');
            $table->set('password')->varchar(32)->comment('微信官网登录密码');
            $table->set('status')->bool()->notNull()->defaultVal(0)->comment('状态');
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
            $table->set('status')->bool()->defaultVal(FansModel::STATUS_SUBSCRIBED)
                ->comment('关注状态');
            $table->set('is_black')->bool()->defaultVal(0)->comment('是否是黑名单');
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
            $table->set('openid')->varchar(50)->notNull()->comment('微信ID');
            $table->set('nickname')->varchar(20)->notNull()->comment('昵称');
            $table->set('sex')->bool()->unsigned()->notNull()->defaultVal(0)->comment('性别');
            $table->set('city')->varchar(40)->notNull()->comment('所在城市');
            $table->set('country')->varchar(40)->notNull()->comment('所在国家');
            $table->set('province')->varchar(40)->notNull()->comment('所在省');
            $table->set('language')->varchar(40)->notNull()->comment('用户语言');
            $table->set('avatar')->varchar()->notNull()->comment('用户头像');
            $table->set('subscribe_time')->int(10)->unsigned()->defaultVal(0)->null();
            $table->set('union_id')->varchar(30)->notNull()->comment('微信ID');
            $table->set('remark')->varchar()->defaultVal('')->comment('备注');
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
            $table->set('mark')->bool()->defaultVal(0)->comment('是否标记');
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
            $table->set('material_type')->bool()->defaultVal(MediaModel::MATERIAL_PERMANENT)
                ->comment('素材类别:永久/临时');
            $table->set('title')->varchar(200)->comment('素材标题');
            $table->set('thumb')->varchar(200)->defaultVal('')->comment('图文的封面');
            $table->set('show_cover')->bool()->defaultVal(0)->comment('显示图文的封面');
            $table->set('open_comment')->bool()->defaultVal(0)->comment('图文是否可以评论');
            $table->set('only_comment')->bool()->defaultVal(0)->comment('图文可以评论的人');
            $table->set('content')->text()->comment('素材内容');
            $table->set('parent_id')->int()->comment('图文父id');
            $table->set('media_id')->varchar(100)->notNull()->comment('素材ID');
            $table->set('url')->varchar()->defaultVal('')->comment('图片的url');
            $table->timestamp('expired_at')->comment('临时素材的过期时间');
            $table->timestamps();
        });
    }
}