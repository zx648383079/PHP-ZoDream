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

        $this->append(ReplyModel::tableName(), function(Table $table) {
            $table->comment('微信回复');
            $table->id();
            $table->uint('wid')->comment('所属微信公众号ID');
            $table->string('event', 20)->comment('事件');
            $table->string('keywords', 60)->default('')->comment('关键词');
            $table->bool('match')->default(0)->comment('关键词匹配模式');
            $table->text('content')->comment('微信返回数据');
            $table->string('type', 10)->comment('素材类型');
            $table->timestamps();
        })->append(MenuModel::tableName(), function(Table $table) {
            $table->comment('微信菜单');
            $table->id();
            $table->uint('wid')->comment('所属微信公众号ID');
            $table->string('name', 100)->comment('菜单名称');
            $table->string('type', 100)->comment('菜单类型');
            $table->text('content')->comment('菜单数据');
            $table->uint('parent_id')->default(0);
            $table->timestamps();
        })->append(MediaTemplateModel::tableName(), function(Table $table) {
            $table->comment('微信图文模板');
            $table->id();
            $table->uint('type', 2)->default(0)->comment('类型：素材、节日、行业');
            $table->uint('category')->default(0)->comment('详细分类');
            $table->string('name', 100)->comment('模板标题');
            $table->text('content')->comment('模板内容');
            $table->timestamps();
        })->append(TemplateModel::tableName(), function(Table $table) {
            $table->comment('微信模板消息模板');
            $table->id();
            $table->uint('wid')->comment('所属微信公众号ID');
            $table->string('template_id', 64)->comment('模板id');
            $table->string('title', 100)->comment('标题');
            $table->string('content')->comment('内容');
            $table->string('example')->comment('示例');
        })->autoUp();
    }


    /**
     * 公众号表
     */
    public function initWeChatTable() {
        $this->append(WeChatModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 40)->comment('公众号名称');
            $table->string('token', 32)->comment('微信服务访问验证token');
            $table->string('access_token')->comment('访问微信服务验证token');
            $table->string('account', 30)->comment('微信号');
            $table->string('original', 40)->comment('原始ID');
            $table->uint('type', 1)->default(0)->comment('公众号类型');
            $table->string('appid', 50)->comment('公众号的AppID');
            $table->string('secret', 50)->comment('公众号的AppSecret');
            $table->string('aes_key', 43)->comment('消息加密秘钥EncodingAesKey');
            $table->string('avatar')->comment('头像地址');
            $table->string('qrcode')->comment('二维码地址');
            $table->string('address')->comment('所在地址');
            $table->string('description')->comment('公众号简介');
            $table->string('username', 40)->comment('微信官网登录名');
            $table->string('password', 32)->comment('微信官网登录密码');
            $table->bool('status')->default(0)->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * 粉丝表
     */
    public function initFansTable() {
        $this->append(FansModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('wid')->comment('所属微信公众号ID');
            $table->string('openid', 50)->comment('微信ID');
            $table->bool('status')->default(FansModel::STATUS_SUBSCRIBED)
                ->comment('关注状态');
            $table->bool('is_black')->default(0)->comment('是否是黑名单');
            $table->timestamps();
        });
    }
    /**
     * 粉丝用户表
     */
    public function initUserTable() {
        // 公众号粉丝详情表
        $this->append(UserModel::tableName(), function(Table $table) {
            $table->id()->comment('粉丝ID');
            $table->string('openid', 50)->comment('微信ID');
            $table->string('nickname', 20)->comment('昵称');
            $table->bool('sex')->default(0)->comment('性别');
            $table->string('city', 40)->comment('所在城市');
            $table->string('country', 40)->comment('所在国家');
            $table->string('province', 40)->comment('所在省');
            $table->string('language', 40)->comment('用户语言');
            $table->string('avatar')->comment('用户头像');
            $table->timestamp('subscribe_time');
            $table->string('union_id', 30)->comment('微信ID');
            $table->string('remark')->default('')->comment('备注');
            $table->uint('group_id');
            $table->timestamp('updated_at');
        });
    }
    /**
     * 消息记录表
     */
    public function initMessageHistoryTable() {
        $this->append(MessageHistoryModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('wid')->comment('所属微信公众号ID');
            $table->uint('rid')->comment('相应规则ID');
            $table->uint('kid')->comment('所属关键字ID');
            $table->string('from', 50)->comment('请求用户ID');
            $table->string('to', 50)->comment('相应用户ID');
            $table->text('message')->comment('消息体内容');
            $table->string('type', 10)->comment('发送类型');
            $table->bool('mark')->default(0)->comment('是否标记');
            $table->timestamp('created_at');
        });
    }
    /**
     * 素材表
     */
    public function initMediaTable() {
        $this->append(MediaModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('wid')->comment('所属微信公众号ID');
            $table->string('type', 10)->comment('素材类型');
            $table->bool('material_type')->default(MediaModel::MATERIAL_PERMANENT)
                ->comment('素材类别:永久/临时');
            $table->string('title', 200)->comment('素材标题');
            $table->string('thumb', 200)->default('')->comment('图文的封面');
            $table->bool('show_cover')->default(0)->comment('显示图文的封面');
            $table->bool('open_comment')->default(0)->comment('图文是否可以评论');
            $table->bool('only_comment')->default(0)->comment('图文可以评论的人');
            $table->column('content')->longtext()->comment('素材内容');
            $table->uint('parent_id')->default(0)->comment('图文父id');
            $table->string('media_id', 100)->default('')->comment('素材ID');
            $table->string('url')->default('')->comment('图片的url');
            $table->timestamp('expired_at')->comment('临时素材的过期时间');
            $table->timestamps();
        });
    }
}