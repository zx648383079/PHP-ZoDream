<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Bot\Domain\Model\EditorTemplateCategoryModel;
use Module\Bot\Domain\Model\EditorTemplateModel;
use Module\Bot\Domain\Model\MediaModel;
use Module\Bot\Domain\Model\MenuModel;
use Module\Bot\Domain\Model\MessageHistoryModel;
use Module\Bot\Domain\Model\QrcodeModel;
use Module\Bot\Domain\Model\TemplateModel;
use Module\Bot\Domain\Model\UserGroupModel;
use Module\Bot\Domain\Model\UserModel;
use Module\Bot\Domain\Model\ReplyModel;
use Module\Bot\Domain\Model\BotModel;
use Module\Bot\Domain\Repositories\BotRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateBotTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        $this->initEditor();
        $this->initBotTable();
        $this->initUserTable();
        $this->initMessageHistoryTable();
        $this->initMediaTable();

        $this->append(ReplyModel::tableName(), function(Table $table) {
            $table->comment('微信回复');
            $table->id();
            $table->uint('bot_id')->comment('所属微信公众号ID');
            $table->string('event', 20)->comment('事件');
            $table->string('keywords', 60)->default('')->comment('关键词');
            $table->bool('match')->default(0)->comment('关键词匹配模式');
            $table->text('content')->comment('微信返回数据');
            $table->uint('type', 1)->default(0)->comment('素材类型');
            $table->bool('status')->default(1)->comment('激活');
            $table->timestamps();
        })->append(MenuModel::tableName(), function(Table $table) {
            $table->comment('微信菜单');
            $table->id();
            $table->uint('bot_id')->comment('所属微信公众号ID');
            $table->string('name', 100)->comment('菜单名称');
            $table->uint('type', 1)->default(0)->comment('菜单类型');
            $table->string('content', 500)->default('')->comment('菜单数据');
            $table->uint('parent_id')->default(0);
            $table->timestamps();
        })->append(TemplateModel::tableName(), function(Table $table) {
            $table->comment('微信模板消息模板');
            $table->id();
            $table->uint('bot_id')->comment('所属微信公众号ID');
            $table->string('template_id', 64)->comment('模板id');
            $table->string('title', 100)->comment('标题');
            $table->string('content')->comment('内容');
            $table->string('example')->default('')->comment('示例');
        })->append(QrcodeModel::tableName(), function(Table $table) {
            $table->comment('微信二维码');
            $table->id();
            $table->uint('bot_id')->comment('所属微信公众号ID');
            $table->string('name')->comment('使用用途');
            $table->bool('type')->default(0)->comment('永久或临时');
            $table->bool('scene_type')->default(0)->comment('数字或字符串');
            $table->string('scene_str')->default('')->comment('场景值');
            $table->uint('scene_id')->default(0)->comment('场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000');
            $table->uint('expire_time')->default(0)->comment('过期事件/s');
            $table->string('qr_url')->default('')->comment('二维码地址');
            $table->string('url')->default('')->comment('解析后的地址');
            $table->timestamps();
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'bot_manage' => 'Bot管理'
        ]);
    }

    public function initEditor(): void {
        $this->append(EditorTemplateModel::tableName(), function(Table $table) {
            $table->comment('微信图文模板');
            $table->id();
            $table->uint('type', 2)->default(0)->comment('类型：素材、节日、行业');
            $table->uint('cat_id')->default(0)->comment('详细分类');
            $table->string('name', 100)->comment('模板标题');
            $table->text('content')->comment('模板内容');
            $table->timestamps();
        })->append(EditorTemplateCategoryModel::tableName(), function(Table $table) {
            $table->comment('微信图文模板分类');
            $table->id();
            $table->string('name', 20)->comment('模板标题');
            $table->uint('parent_id')->default(0);
        });
    }

    /**
     * 公众号表
     */
    public function initBotTable(): void {
        $this->append(BotModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('platform_type', 1)
                ->default(BotRepository::PLATFORM_TYPE_WX)->comment('公众号平台类型');
            $table->string('name', 40)->comment('公众号名称');
            $table->string('token', 32)->comment('微信服务访问验证token');
            $table->string('access_token')->default('')->comment('访问微信服务验证token');
            $table->string('account', 30)->default('')->comment('微信号');
            $table->string('original', 40)->default('')->comment('原始ID');
            $table->uint('type', 1)->default(0)->comment('公众号类型');
            $table->string('appid', 50)->default('')->comment('公众号的AppID');
            $table->string('secret', 50)->default('')->comment('公众号的AppSecret');
            $table->string('aes_key', 43)->default('')->comment('消息加密秘钥EncodingAesKey');
            $table->string('avatar')->default('')->comment('头像地址');
            $table->string('qrcode')->default('')->comment('二维码地址');
            $table->string('address')->default('')->comment('所在地址');
            $table->string('description')->default('')->comment('公众号简介');
            $table->string('username', 40)->default('')->comment('微信官网登录名');
            $table->string('password', 32)->default('')->comment('微信官网登录密码');
            $table->bool('status')->default(BotModel::STATUS_INACTIVE)->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * 粉丝用户表
     */
    public function initUserTable(): void {
        // 公众号粉丝详情表
        $this->append(UserModel::tableName(), function(Table $table) {
            $table->id()->comment('粉丝ID');
            $table->string('openid', 50)->comment('微信ID');
            $table->string('nickname', 20)->default('')->comment('昵称');
            $table->bool('sex')->default(0)->comment('性别');
            $table->string('city', 40)->default('')->comment('所在城市');
            $table->string('country', 40)->default('')->comment('所在国家');
            $table->string('province', 40)->default('')->comment('所在省');
            $table->string('language', 40)->default('')->comment('用户语言');
            $table->string('avatar')->comment('用户头像');
            $table->timestamp('subscribe_at');
            $table->string('union_id', 30)->default('')->comment('微信ID');
            $table->string('remark')->default('')->comment('备注');
            $table->uint('group_id')->default(0);
            $table->uint('bot_id')->comment('所属微信公众号ID');
            $table->string('note_name', 20)->default('')->comment('备注名');
            $table->bool('status')->default(UserModel::STATUS_SUBSCRIBED)
                ->comment('关注状态');
            $table->bool('is_black')->default(0)->comment('是否是黑名单');
            $table->timestamps();
        })->append(UserGroupModel::tableName(), function (Table $table) {
            $table->id()->comment('分组');
            $table->uint('bot_id')->comment('所属微信公众号ID');
            $table->string('name', 20)->comment('名称');
            $table->string('tag_id', 20)->default('')->comment('公众平台标签id');
        });
    }
    /**
     * 消息记录表
     */
    public function initMessageHistoryTable(): void {
        $this->append(MessageHistoryModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('bot_id')->comment('所属微信公众号ID');
            $table->uint('type', 1)->default(0)->comment('消息类型');
            $table->uint('item_type', 1)->default(0)->comment('发送类型');
            $table->uint('item_id')->default(0)->comment('相应规则ID');
            $table->string('from', 50)->comment('请求用户ID');
            $table->string('to', 50)->comment('相应用户ID');
            $table->text('content')->nullable()->comment('消息体内容');
            $table->bool('is_mark')->default(0)->comment('是否标记');
            $table->timestamp('created_at');
        });
    }
    /**
     * 素材表
     */
    public function initMediaTable(): void {
        $this->append(MediaModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('bot_id')->comment('所属微信公众号ID');
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
            $table->uint('publish_status', 1)->default(0)->comment('当前发布的状态');
            $table->string('publish_id', 50)->default('')->comment('当前发布的id, publish_status=6,为草稿=7,为发布中publish_id；为草稿=8,为发布成功，article_id');
            $table->timestamp('expired_at')->comment('临时素材的过期时间');
            $table->timestamps();
        });
    }
}