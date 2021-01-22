<?php
namespace Module\Video\Domain\Migrations;

use Module\Video\Domain\Models\CommentModel;
use Module\Video\Domain\Models\LogModel;
use Module\Video\Domain\Models\MusicModel;
use Module\Video\Domain\Models\VideoModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateVideoTables extends Migration {

    public function up() {
        $this->append(MusicModel::tableName(), function (Table $table) {
            $table->setComment('背景音乐库');
            $table->set('id')->pk(true);
            $table->set('name')->varchar()->notNull()->comment('歌曲名');
            $table->set('singer')->varchar(20)->defaultVal('')
                ->comment('歌手');
            $table->set('duration')->smallInt(4)->defaultVal(0)->comment('歌曲长度');
            $table->set('path')->varchar()->notNull()->comment('歌曲路径');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamps();
        })->append(VideoModel::tableName(), function (Table $table) {
            $table->setComment('短视频库');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('cover')->varchar()->defaultVal('')
                ->comment('封面');
            $table->set('content')->varchar()->defaultVal('')
                ->comment('介绍');
            $table->set('video_path')->varchar()->notNull()
                ->comment('视频路径');
            $table->set('video_duration')->smallInt(4)
                ->defaultVal(0)->comment('视频长度');
            $table->set('video_height')->smallInt(4)
                ->defaultVal(0)->comment('视频高度');
            $table->set('video_width')->smallInt(4)
                ->defaultVal(0)->comment('视频宽度');
            $table->set('music_id')->int()->notNull();
            $table->set('music_offset')->smallInt(3)->defaultVal(0)
                ->comment('歌曲开始时间');
            $table->set('like_count')->int()->defaultVal(0)
                ->comment('喜欢的人数');
            $table->set('comment_count')->int()->defaultVal(0)
                ->comment('评论的人数');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamps();
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('content')->varchar()->notNull();
            $table->set('parent_id')->int(10);
            $table->set('user_id')->int(10)->defaultVal(0);
            $table->set('video_id')->int(10)->notNull();
            $table->set('agree')->int(10)->defaultVal(0);
            $table->set('disagree')->int(10)->defaultVal(0);
            $table->timestamp('created_at');
        })->append(LogModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('item_type')->tinyint(1)->defaultVal(0);
            $table->set('item_id')->int(10)->notNull();
            $table->set('user_id')->int(10)->notNull();
            $table->set('action')->int(10)->notNull();
            $table->timestamp('created_at');
        })->autoUp();
    }
}
