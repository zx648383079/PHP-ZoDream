<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\OnlineTV\Domain\Models\AreaModel;
use Module\OnlineTV\Domain\Models\CategoryModel;
use Module\OnlineTV\Domain\Models\LiveModel;
use Module\OnlineTV\Domain\Models\MovieFileModel;
use Module\OnlineTV\Domain\Models\MovieModel;
use Module\OnlineTV\Domain\Models\MovieScoreModel;
use Module\OnlineTV\Domain\Models\MovieSeriesModel;
use Module\OnlineTV\Domain\Models\MusicFileModel;
use Module\OnlineTV\Domain\Models\MusicModel;
use Module\OnlineTV\Domain\Repositories\TVRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTVTables extends Migration {

    public function up() {
        TVRepository::log()->migration($this);
        TVRepository::tag()->migration($this);
        $this->append(CategoryModel::tableName(), function(Table $table) {
            $table->comment('分类');
            $table->id();
            $table->string('name', 30);
            $table->string('icon')->default('');
            $table->uint('parent_id')->default(0);
        })->append(AreaModel::tableName(), function(Table $table) {
            $table->comment('地区');
            $table->id();
            $table->string('name', 30);;
        })->append(LiveModel::tableName(), function(Table $table) {
            $table->comment('直播源');
            $table->id();
            $table->string('title');
            $table->string('thumb')->default('');
            $table->string('source');
            $table->bool('status')->default(1);
            $table->timestamps();
        })->append(MusicModel::tableName(), function(Table $table) {
            $table->comment('音乐');
            $table->id();
            $table->string('name')->comment('歌曲名');
            $table->string('cover')->default('')->comment('封面');
            $table->string('album', 20)->default('')->comment('专辑');
            $table->string('artist', 20)->default('')->comment('演唱者');
            $table->uint('duration', 6)->default(0)->comment('歌曲长度');
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(MusicFileModel::tableName(), function(Table $table) {
            $table->comment('音乐文件');
            $table->id();
            $table->uint('music_id');
            $table->uint('file_type', 1)->default(0)->comment('文件类型,不同音质,歌词');
            $table->string('file');
            $table->timestamp('created_at');
        })->append(MovieModel::tableName(), function(Table $table) {
            $table->comment('影视');
            $table->id();
            $table->string('title');
            $table->string('film_title')->default('');
            $table->string('translation_title')->default('');
            $table->string('cover')->default('')->comment('封面');
            $table->string('director')->default('')->comment('导演');
            $table->string('leader', 500)->default('')->comment('主演');
            $table->string('screenwriter')->default('')->comment('编剧');
            $table->uint('cat_id')->default(0);
            $table->uint('area_id')->default(0);
            $table->char('age', 4)->default(date('Y'));
            $table->string('language', 10)->default('');
            $table->string('release_date')->default('')->comment('上映日期');
            $table->uint('duration', 6)->default(0)->comment('时长');
            $table->string('description')->default('');
            $table->text('content')->nullable();
            $table->uint('series_count', 4)->default(1)->comment('一集就是电影');
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(MovieScoreModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('movie_id');
            $table->string('name', 20);
            $table->string('score', 10);
            $table->string('url')->default('')->comment('评分站点页面');
            $table->timestamps();
        })->append(MovieSeriesModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('movie_id');
            $table->uint('episode', 4)->comment('第几集');
            $table->string('title')->default('');
            $table->timestamps();
        })->append(MovieFileModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name');
            $table->uint('movie_id');
            $table->uint('series_id')->default(0);
            $table->uint('file_type', 1)->default(0)
                ->comment('文件类型,文件还是种子');
            $table->uint('definition', 1)->default(0)->comment('清晰度');
            $table->string('file');
            $table->string('size', 20)->default('0');
            $table->string('subtitle_file')->default('')->comment('字幕');
        })->autoUp();
    }

    public function seed()
    {
        RoleRepository::newPermission([
            'tv_manage' => 'TV管理'
        ]);
    }
}