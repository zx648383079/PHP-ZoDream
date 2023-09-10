<?php
declare(strict_types=1);
namespace Module\Code\Domain\Migrations;

use Module\Code\Domain\Model\CodeModel;
use Module\Code\Domain\Repositories\CodeRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCodeTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        CodeRepository::comment()->migration($this);
        CodeRepository::tag()->migration($this);
        $this->append(CodeModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->text('content');
            $table->string('language', 20)->default('')->comment('语言');
            $table->uint('recommend_count')->default(0)->comment('推荐数');
            $table->uint('collect_count')->default(0)->comment('收藏数');
            $table->uint('comment_count')->default(0)->comment('评论数');
            $table->string('source')->default('')->comment('来源');
            $table->timestamps();
        })->autoUp();
    }
}