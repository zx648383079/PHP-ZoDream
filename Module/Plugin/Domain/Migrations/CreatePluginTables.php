<?php
declare(strict_types=1);
namespace Module\Plugin\Domain\Migrations;


use Module\Plugin\Domain\Entities\PluginEntity;
use Module\Plugin\Domain\Entities\PluginLogEntity;
use Zodream\Database\Contracts\Table;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Model\Model;

final class CreatePluginTables extends Migration {

    public function up(): void {
        $this->append(PluginEntity::tableName(), function (Table $table) {
            $table->comment('插件表');
            $table->id();
            $table->string('name');
            $table->string('description')->default('');
            $table->string('author')->default('');
            $table->string('version')->default('');
            $table->string('path')->comment('入口文件');
            $table->bool('status')->default(0);
            $table->text('configs')->nullable();
            $table->timestamps();
        })->append(PluginLogEntity::tableName(), function (Table $table) {
            $table->comment('插件执行表');
            $table->id();
            $table->uint('plugin_id');
            $table->bool('status')->default(0)->comment('执行状态');
            $table->text('content')->nullable();
            $table->timestamp(Model::CREATED_AT);
        })->autoUp();
    }
}