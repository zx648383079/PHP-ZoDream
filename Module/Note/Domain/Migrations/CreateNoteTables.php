<?php
declare(strict_types=1);
namespace Module\Note\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Note\Domain\Repositories\NoteRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\Note\Domain\Model\NoteModel;

class CreateNoteTables extends Migration {

    public function up(): void {
        $this->append(NoteModel::tableName(), function(Table $table) {
            $table->comment('便签');
            $table->id();
            $table->string('content')->comment('内容');
            $table->uint('user_id');
            $table->bool('is_notice')->default(0)->comment('是否时站点公告');
            $table->uint('status', 1)->default(NoteRepository::STATUS_VISIBLE)->comment('发布状态,');
            $table->timestamp('created_at');
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'note_manage' => '便签管理'
        ]);
    }
}