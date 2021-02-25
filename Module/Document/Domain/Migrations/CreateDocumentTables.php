<?php
namespace Module\Document\Domain\Migrations;


use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Model\PageModel;
use Module\Document\Domain\Model\ProjectModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateDocumentTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->append(ProjectModel::tableName(), function(Table $table) {
            $table->comment('项目表');
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(35)->comment('账户名');
            $table->column('type')->tinyint(1)->default(0)->comment('文档类型');
            $table->column('description')->varchar()->default('')->comment('描述');
            $table->column('environment')->text()->nullable()->comment('环境域名,json字符串');
            $table->uint('status', 2)->default(0)->comment('是否可见');
            $table->softDeletes();
            $table->timestamps();
        })->append(ApiModel::tableName(), function(Table $table) {
            $table->comment('项目接口表');
            $table->id();
            $table->column('name')->varchar(35)->comment('接口名');
            $table->column('method')->varchar(10)->default('POST')->comment('请求方式');
            $table->column('uri')->varchar()->default('')->comment('接口地址');
            $table->uint('project_id')->comment('项目');
            $table->column('description')->varchar()->comment('接口简介');
            $table->uint('parent_id')->default(0);
            $table->timestamps();
        })->append(PageModel::tableName(), function(Table $table) {
            $table->comment('项目普通文档页');
            $table->id();
            $table->column('name')->varchar(35)->comment('文档');
            $table->uint('project_id')->comment('项目');
            $table->uint('parent_id')->default(0);
            $table->column('content')->text()->comment('内容');
            $table->timestamps();
        })->append(FieldModel::tableName(), function(Table $table) {
            $table->comment('项目字段表');
            $table->id();
            $table->column('name')->varchar(50)->comment('接口名称');
            $table->column('title')->varchar(50)->default('')->comment('接口标题');
            $table->column('is_required')->bool()->default(1)->comment('是否必传');
            $table->column('default_value')->varchar()->default('')->comment('默认值');
            $table->column('mock')->varchar()->default('')->comment('mock规则');
            $table->uint('parent_id')->default(0);
            $table->uint('api_id')->comment('接口id');
            $table->column('kind')->tinyint(3)->default(1)->comment('参数类型，1:请求字段 2:响应字段 3:header字段');
            $table->column('type')->varchar(10)->default('')->comment('字段类型');
            $table->column('remark')->text()->nullable()->comment('备注');
            $table->timestamps();
        })->autoUp();

    }
}