<?php
namespace Module\SEO\Domain\Migrations;

use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\SEO\Domain\Model\OptionModel;


class CreateSEOTables extends Migration {

    public function up() {
        $this->append(OptionModel::tableName(), function(Table $table) {
            $table->comment('全局设置');
            $table->id();
            $table->column('name')->varchar(20);
            $table->column('code')->varchar(20)->default('');
            $table->uint('parent_id')->default(0);
            $table->column('type')->varchar(20)->default('text');
            $table->column('visibility')->bool()->default(1)->comment('是否对外显示');
            $table->column('default_value')->varchar(255)->default('')->comment('默认值或候选值');
            $table->column('value')->text()->nullable();
            $table->uint('position', 2)->default(99);
        })->autoUp();
    }

    public function seed() {
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