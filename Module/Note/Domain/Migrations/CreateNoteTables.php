<?php
declare(strict_types=1);
namespace Module\Note\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Note\Domain\Repositories\NoteRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\Note\Domain\Entities\NoteEntity;
use Module\Note\Domain\Entities\TopicEntity;

class CreateNoteTables extends Migration {

    public function up(): void {
        $this->append(NoteEntity::tableName(), function(Table $table) {
            $table->comment('便签');
            $table->id();
            $table->string('content')->comment('内容');
            $table->string('extra_rule')->default('')
                ->comment('内容的一些附加规则');
            $table->uint('user_id');
            $table->uint('topic_id')->default(0);
            $table->string('properties', 200)->default('')->comment('属性');
            $table->uint('status', 1)->default(NoteRepository::STATUS_VISIBLE)->comment('发布状态,');
            $table->timestamps();
        })->append(TopicEntity::tableName(), function(Table $table) {
            $table->comment('专栏');
            $table->id()->ai(10);
            $table->string('title', 200);
            $table->string('description')->default('');
            $table->uint('user_id')->default(0);
            $table->uint('status', 1)->default(NoteRepository::STATUS_VISIBLE)->comment('发布状态,');
            $table->timestamp('created_at');
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'note_manage' => '便签管理'
        ]);
        if (TopicEntity::query()->count() > 0) {
            return;
        }
        TopicEntity::query()->insert([
            [
                'id' => 1,
                'title' => '系统通知'
            ],
            [
                'id' => 2,
                'title' => 'System Notice'
            ]
        ]);
    }
}