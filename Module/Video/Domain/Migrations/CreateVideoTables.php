<?php
declare(strict_types=1);
namespace Module\Video\Domain\Migrations;

use Module\AppStore\Domain\Repositories\AppRepository;
use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Video\Domain\Models\MusicModel;
use Module\Video\Domain\Models\VideoModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateVideoTables extends Migration {

    public function up() {
        AppRepository::comment()->migration($this);
        AppRepository::tag()->migration($this);
        $this->append(MusicModel::tableName(), function (Table $table) {
            $table->comment('背景音乐库');
            $table->id();
            $table->string('name')->comment('歌曲名');
            $table->string('singer', 20)->default('')
                ->comment('歌手');
            $table->uint('duration', 4)->default(0)->comment('歌曲长度');
            $table->string('path')->comment('歌曲路径');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(VideoModel::tableName(), function (Table $table) {
            $table->comment('短视频库');
            $table->id();
            $table->uint('user_id');
            $table->string('cover')->default('')
                ->comment('封面');
            $table->string('content')->default('')
                ->comment('介绍');
            $table->string('video_path')
                ->comment('视频路径');
            $table->uint('video_duration', 4)
                ->default(0)->comment('视频长度');
            $table->uint('video_height', 4)
                ->default(0)->comment('视频高度');
            $table->uint('video_width', 4)
                ->default(0)->comment('视频宽度');
            $table->uint('music_id');
            $table->uint('music_offset', 3)->default(0)
                ->comment('歌曲开始时间');
            $table->uint('like_count')->default(0)
                ->comment('喜欢的人数');
            $table->uint('comment_count')->default(0)
                ->comment('评论的人数');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->autoUp();
    }

    public function seed() {
        RoleRepository::newPermission([
            'video_manage' => '短视频管理'
        ]);
    }
}
