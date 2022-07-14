<?php
namespace Module\Document\Domain\Migrations;


use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\CategoryModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Model\PageModel;
use Module\Document\Domain\Model\ProjectModel;
use Module\Document\Domain\Model\ProjectVersionModel;
use Module\Document\Domain\Repositories\ProjectRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateDocumentTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        ProjectRepository::comment()->migration($this);
        $this->append(CategoryModel::tableName(), function(Table $table) {
            $table->comment('分类');
            $table->id();
            $table->string('name', 30);
            $table->string('icon')->default('');
            $table->uint('parent_id')->default(0);
        })->append(ProjectModel::tableName(), function(Table $table) {
            $table->comment('项目表');
            $table->id();
            $table->uint('user_id');
            $table->uint('cat_id');
            $table->string('name', 35)->comment('项目名');
            $table->string('cover', 200)->default('')
                ->comment('项目封面');
            $table->uint('type', 1)->default(0)->comment('文档类型');
            $table->string('description')->default('')->comment('描述');
            $table->text('environment')->nullable()->comment('环境域名,json字符串');
            $table->uint('status', 2)->default(0)->comment('是否可见');
            $table->softDeletes();
            $table->timestamps();
        })->append(ProjectVersionModel::tableName(), function(Table $table) {
            $table->comment('项目版本表');
            $table->id();
            $table->uint('project_id');
            $table->string('name', 20)->comment('版本号');
            $table->timestamps();
        })->append(ApiModel::tableName(), function(Table $table) {
            $table->comment('项目接口表');
            $table->id();
            $table->string('name', 35)->comment('接口名');
            $table->bool('type')->default(0)->comment('是否有内容,0为有内容');
            $table->string('method', 10)->default('POST')->comment('请求方式');
            $table->string('uri')->default('')->comment('接口地址');
            $table->uint('project_id')->comment('项目');
            $table->uint('version_id')->default(0)->comment('版本');
            $table->string('description')->default('')->comment('接口简介');
            $table->uint('parent_id')->default(0);
            $table->timestamps();
        })->append(PageModel::tableName(), function(Table $table) {
            $table->comment('项目普通文档页');
            $table->id();
            $table->string('name', 35)->comment('文档');
            $table->uint('project_id')->comment('项目');
            $table->uint('version_id')->default(0)->comment('版本');
            $table->uint('parent_id')->default(0);
            $table->bool('type')->default(0)->comment('是否有内容,0为有内容');
            $table->text('content')->nullable()->comment('内容');
            $table->timestamps();
        })->append(FieldModel::tableName(), function(Table $table) {
            $table->comment('项目字段表');
            $table->id();
            $table->string('name', 50)->comment('接口名称');
            $table->string('title', 50)->default('')->comment('接口标题');
            $table->bool('is_required')->bool()->default(1)->comment('是否必传');
            $table->string('default_value')->default('')->comment('默认值');
            $table->string('mock')->default('')->comment('mock规则');
            $table->uint('parent_id')->default(0);
            $table->uint('api_id')->comment('接口id');
            $table->uint('kind', 2)->default(1)->comment('参数类型，1:请求字段 2:响应字段 3:header字段');
            $table->string('type', 10)->default('')->comment('字段类型');
            $table->text('remark')->nullable()->comment('备注');
            $table->timestamps();
        })->autoUp();
    }

    public function seed()
    {
        RoleRepository::newRole('doc_admin', '文档管理员');
    }
}