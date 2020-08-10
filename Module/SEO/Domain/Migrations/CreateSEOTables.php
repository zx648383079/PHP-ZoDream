<?php
namespace Module\SEO\Domain\Migrations;

use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\SEO\Domain\Model\OptionModel;


class CreateSEOTables extends Migration {

    public function up() {
        $this->append(OptionModel::tableName(), function(Table $table) {
            $table->setComment('全局设置');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(20)->notNull();
            $table->set('code')->varchar(20)->defaultVal('');
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('type')->varchar(20)->defaultVal('text');
            $table->set('visibility')->bool()->defaultVal(1)->comment('是否对外显示');
            $table->set('default_value')->varchar(255)->defaultVal('')->comment('默认值或候选值');
            $table->set('value')->text();
            $table->set('position')->tinyint(4)->defaultVal(99);
        });
        parent::up();
    }

    public function seed() {
        if (OptionModel::query()->count() > 0) {
            return;
        }
        OptionModel::group('基本', function ($parent_id) {
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
        OptionModel::group('高级', function ($parent_id) {
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