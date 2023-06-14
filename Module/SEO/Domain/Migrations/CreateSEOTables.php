<?php
namespace Module\SEO\Domain\Migrations;

use Domain\Providers\StorageProvider;
use Domain\Repositories\LocalizeRepository;
use Module\Auth\Domain\Repositories\RoleRepository;
use Module\SEO\Domain\Model\AgreementModel;
use Module\SEO\Domain\Model\BlackWordModel;
use Module\SEO\Domain\Model\EmojiCategoryModel;
use Module\SEO\Domain\Model\EmojiModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\SEO\Domain\Model\OptionModel;


class CreateSEOTables extends Migration {

    public function up() {
        StorageProvider::privateStore()->migration($this);
        $this->append(OptionModel::tableName(), function(Table $table) {
            $table->comment('全局设置');
            $table->id();
            $table->string('name', 20);
            $table->string('code', 50)->default('');
            $table->uint('parent_id')->default(0);
            $table->string('type', 20)->default('text');
            $table->uint('visibility', 1)->default(1)->comment('是否对外显示, 0 页面不可见，1 编辑可见 2 前台可见');
            $table->string('default_value')->default('')->comment('默认值或候选值');
            $table->text('value')->nullable();
            $table->uint('position', 2)->default(99);
        })->append(BlackWordModel::tableName(), function(Table $table) {
            $table->comment('违禁词');
            $table->id();
            $table->string('words');
            $table->string('replace_words')->default('');
        })->append(EmojiModel::tableName(), function(Table $table) {
            $table->comment('表情');
            $table->id();
            $table->uint('cat_id');
            $table->string('name', 30);
            $table->uint('type', 1)->default(0)->comment('图片或文字');
            $table->string('content')->default('');
        })->append(EmojiCategoryModel::tableName(), function(Table $table) {
            $table->comment('表情分类');
            $table->id();
            $table->string('name', 20);
            $table->string('icon')->default('');
        })->append(AgreementModel::tableName(), function(Table $table) {
            $table->comment('服务协议');
            $table->id();
            $table->string('name', 20);
            $table->string('title', 100);
            LocalizeRepository::addTableColumn($table);
            $table->string('description', 500)->default('');
            $table->column('content')->mediumText();
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->autoUp();
    }

    public function seed() {
        RoleRepository::newPermission([
            'system_manage' => '系统配置'
        ]);
        if (OptionModel::query()->count() > 0) {
            return;
        }
        OptionModel::group('基本', function () {
            return [
                [
                    'name' => '站点名',
                    'code' => 'site_title',
                    'visibility' => 2,
                ],
                [
                    'name' => '站点关键字',
                    'code' => 'site_keywords',
                    'visibility' => 2,
                ],
                [
                    'name' => '站点介绍',
                    'code' => 'site_description',
                    'type' => 'textarea',
                    'visibility' => 2,
                ],
                [
                    'name' => '站点LOGO',
                    'code' => 'site_logo',
                    'type' => 'image',
                    'visibility' => 2,
                ],
                [
                    'name' => 'ICP备案号',
                    'code' => 'site_icp_beian',
                    'visibility' => 2,
                ],
                [
                    'name' => '公网安备案号',
                    'code' => 'site_pns_beian',
                    'visibility' => 2,
                ],
            ];
        });
        OptionModel::group('上传', function () {
            return [
                [
                    'name' => '添加水印',
                    'code' => 'upload_add_water',
                    'type' => 'switch',
                    'value' => 0,
                    'visibility' => 1,
                ],
                [
                    'name' => '水印文字',
                    'code' => 'upload_water_text',
                    'type' => 'text',
                    'visibility' => 1,
                ],
                [
                    'name' => '水印位置',
                    'code' => 'upload_water_position',
                    'type' => 'select',
                    'value' => 0,
                    'default_value' => "左上\n右上\n左下\n右下",
                    'visibility' => 1,
                ],
            ];
        });
        OptionModel::group('高级', function () {
            return [
                [
                    'name' => '关站',
                    'code' => 'site_close',
                    'type' => 'switch',
                    'value' => 0,
                    'visibility' => 2,
                ],
                [
                    'name' => '关站说明',
                    'code' => 'site_close_tip',
                    'type' => 'basic_editor',
                    'visibility' => 2,
                ],
                [
                    'name' => '预计开站时间',
                    'code' => 'site_close_retry',
                    'type' => 'text',
                    'visibility' => 2,
                ],
                [
                    'name' => '开启灰度',
                    'code' => 'site_gray',
                    'type' => 'switch',
                    'value' => 0,
                    'visibility' => 2,
                ],
            ];
        });
    }

}