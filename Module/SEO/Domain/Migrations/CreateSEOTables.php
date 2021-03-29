<?php
namespace Module\SEO\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\SEO\Domain\Model\OptionModel;


class CreateSEOTables extends Migration {

    public function up() {
        $this->append(OptionModel::tableName(), function(Table $table) {
            $table->comment('全局设置');
            $table->id();
            $table->string('name', 20);
            $table->string('code', 20)->default('');
            $table->uint('parent_id')->default(0);
            $table->string('type', 20)->default('text');
            $table->bool('visibility')->default(1)->comment('是否对外显示');
            $table->string('default_value')->default('')->comment('默认值或候选值');
            $table->text('value')->nullable();
            $table->uint('position', 2)->default(99);
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
                ],
                [
                    'name' => '站点关键字',
                    'code' => 'site_keywords',
                ],
                [
                    'name' => '站点介绍',
                    'code' => 'site_description',
                    'type' => 'textarea',
                ],
                [
                    'name' => '站点LOGO',
                    'code' => 'site_logo',
                    'type' => 'image',
                ],
            ];
        });
        OptionModel::group('高级', function () {
            return [
                [
                    'name' => '关站',
                    'code' => 'site_close',
                    'type' => 'switch',
                    'value' => 0
                ],
                [
                    'name' => '关站说明',
                    'code' => 'site_close_tip',
                    'type' => 'basic_editor'
                ],
                [
                    'name' => '预计开站时间',
                    'code' => 'site_close_retry',
                    'type' => 'text'
                ],
                [
                    'name' => '开启灰度',
                    'code' => 'site_gray',
                    'type' => 'switch',
                    'value' => 0
                ],
            ];
        });
    }

}