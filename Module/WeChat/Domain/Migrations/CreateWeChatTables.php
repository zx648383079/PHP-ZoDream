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
            $table->column('event')->varchar(20)->comment('事件');
            $table->column('keywords')->varchar(60)->default('')->comment('关键词');
            $table->column('match')->bool()->default(0)->comment('关键词匹配模式');
            $table->column('content')->text()->comment('微信返回数据');
            $table->column('type')->varchar(10)->comment('素材类型');
            $table->timestamps();
        })->append(MenuModel::tableName(), function(Table $table) {
            $table->comment('微信菜单');
            $table->id();
            $table->uint('wid')->comment('所属微信公众号ID');
            $table->column('name')->varchar(100)->comment('菜单名称');
            $table->column('type')->varchar(100)->comment('菜单类型');
            $table->column('content')->text()->comment('菜单数据');
            $table->uint('parent_id')->default(0);
            $table->timestamps();
        })->append(MediaTemplateModel::tableName(), function(Table $table) {
            $table->comment('微信图文模板');
            $table->id();
            $table->uint('type', 2)->default(0)->comment('类型：素材、节日、行业');
            $table->uint('category')->default(0)->comment('详细分类');
            $table->column('name')->varchar(100)->comment('模板标题');
            $table->column('content')->text()->comment('模板内容');
            $table->timestamps();
        })->append(TemplateModel::tableName(), function(Table $table) {
            $table->comment('微信模板消息模板');
            $table->id();
            $table->uint('wid')->comment('所属微信公众号ID');
            $table->column('template_id')->varchar(64)->comment('模板id');
            $table->column('title')->varchar(100)->comment('标题');
            $table->column('content')->varchar(255)->comment('内容');
            $table->column('example')->varchar(255)->comment('示例');
        })->autoUp();
    }


    /**
     * 公众号表
     */
    public function initWeChatTable() {
        $this->append(WeChatModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar(40)->comment('公众号名称');
            $table->column('token')->varchar(32)->comment('微信服务访问验证token');
            $table->column('access_token')->varchar()->comment('访问微信服务验证token');
            $table->column('account')->varchar(30)->comment('微信号');
            $table->column('original')->varchar(40)->comment('原始ID');
            $table->uint('type', 1)->default(0)->comment('公众号类型');
            $table->column('appid')->varchar(50)->comment('公众号的AppID');
            $table->column('secret')->varchar(50)->comment('公众号的AppSecret');
            $table->column('aes_key')->varchar(43)->comment('消息加密秘钥EncodingAesKey');
            $table->column('avatar')->varchar()->comment('头像地址');
            $table->column('qrcode')->varchar()->comment('二维码地址');
            $table->column('address')->varchar()->comment('所在地址');
            $table->column('description')->varchar()->comment('公众号简介');
            $table->column('username')->varchar(40)->comment('微信官网登录名');
            $table->column('password')->varchar(32)->comment('微信官网登录密码');
            $table->column('status')->bool()->default(0)->comment('状态');
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
            $table->column('openid')->varchar(50)->comment('微信ID');
            $table->column('status')->bool()->default(FansModel::STATUS_SUBSCRIBED)
                ->comment('关注状态');
            $table->column('is_black')->bool()->default(0)->comment('是否是黑名单');
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
            $table->column('openid')->varchar(50)->comment('微信ID');
            $table->column('nickname')->varchar(20)->comment('昵称');
            $table->column('sex')->bool()->unsigned()->default(0)->comment('性别');
            $table->column('city')->varchar(40)->comment('所在城市');
            $table->column('country')->varchar(40)->comment('所在国家');
            $table->column('province')->varchar(40)->comment('所在省');
            $table->column('language')->varchar(40)->comment('用户语言');
            $table->column('avatar')->varchar()->comment('用户头像');
            $table->timestamp('subscribe_time');
            $table->column('union_id')->varchar(30)->comment('微信ID');
            $table->column('remark')->varchar()->default('')->comment('备注');
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
            $table->column('from')->varchar(50)->comment('请求用户ID');
            $table->column('to')->varchar(50)->comment('相应用户ID');
            $table->column('message')->text()->comment('消息体内容');
            $table->column('type')->varchar(10)->comment('发送类型');
            $table->column('mark')->bool()->default(0)->comment('是否标记');
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
            $table->column('type')->varchar(10)->comment('素材类型');
            $table->column('material_type')->bool()->default(MediaModel::MATERIAL_PERMANENT)
                ->comment('素材类别:永久/临时');
            $table->column('title')->varchar(200)->comment('素材标题');
            $table->column('thumb')->varchar(200)->default('')->comment('图文的封面');
            $table->column('show_cover')->bool()->default(0)->comment('显示图文的封面');
            $table->column('open_comment')->bool()->default(0)->comment('图文是否可以评论');
            $table->column('only_comment')->bool()->default(0)->comment('图文可以评论的人');
            $table->column('content')->longtext()->comment('素材内容');
            $table->uint('parent_id')->default(0)->comment('图文父id');
            $table->column('media_id')->varchar(100)->default('')->comment('素材ID');
            $table->column('url')->varchar()->default('')->comment('图片的url');
            $table->timestamp('expired_at')->comment('临时素材的过期时间');
            $table->timestamps();
        });
    }
}