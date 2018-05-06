<?php
namespace Module\Document\Domain\Migrations;


use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Model\ProjectModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateDocumentTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::createTable(ProjectModel::tableName(), function(Table $table) {
            $table->setComment('项目表');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(35)->notNull()->comment('账户名');
            $table->set('description')->varchar()->comment('描述');
            $table->set('environment')->text()->comment('环境域名,json字符串');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(ApiModel::tableName(), function(Table $table) {
            $table->setComment('项目接口表');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(35)->notNull()->comment('接口名');
            $table->set('method')->varchar(10)->defaultVal('POST')->comment('请求方式');
            $table->set('uri')->varchar()->defaultVal('')->comment('接口地址');
            $table->set('project_id')->int()->notNull()->comment('项目');
            $table->set('description')->varchar()->comment('接口简介');
            $table->set('parent_id')->int()->defaultVal(0);
            $table->timestamps();
        });
        Schema::createTable(FieldModel::tableName(), function(Table $table) {
            $table->setComment('项目字段表');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(50)->notNull()->comment('接口名称');
            $table->set('title')->varchar(50)->notNull()->comment('接口标题');
            $table->set('is_required')->bool()->defaultVal(1)->comment('是否必传');
            $table->set('default_value')->varchar()->defaultVal('')->comment('默认值');
            $table->set('mock')->varchar()->defaultVal('')->comment('mock规则');
            $table->set('parent_id')->int()->notNull()->defaultVal(0);
            $table->set('api_id')->int()->notNull()->comment('接口id');
            $table->set('kind')->tinyint(3)->defaultVal(1)->comment('参数类型，1:请求字段 2:响应字段 3:header字段');
            $table->set('type')->varchar(10)->defaultVal('')->comment('字段类型');
            $table->set('remark')->text()->comment('备注');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(ProjectModel::tableName());
        Schema::dropTable(ApiModel::tableName());
        Schema::dropTable(FieldModel::tableName());
    }
}