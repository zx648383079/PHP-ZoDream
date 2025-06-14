<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Exception;
use Module\OnlineTV\Domain\Models\AreaModel;
use Module\OnlineTV\Domain\Models\MovieFileModel;
use Module\OnlineTV\Domain\Models\MovieModel;
use Module\OnlineTV\Domain\Models\MovieScoreModel;
use Module\OnlineTV\Domain\Models\MovieSeriesModel;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Validate\Validator;

final class MovieRepository extends CRUDRepository {

    const MOVIE_PAGE_FILED = [
        'id', 'title', 'film_title', 'cover', 'director', 'leader', 'cat_id', 'area_id', 'age', 'language', 'release_date', 'duration', 'description', 'series_count', 'status', 'updated_at', 'created_at'];

    protected static array $searchKeys = ['title', 'film_title', 'translation_title'];

    public static function seriesList(int $movie, string $keywords = '') {
        return MovieSeriesModel::where('movie_id', $movie)
            ->when(!empty($keywords), function($query) use ($keywords) {
                SearchModel::searchWhere($query, ['title'], false, '', $keywords);
            })
            ->orderBy('id', 'asc')
            ->page();
    }

    public static function seriesSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = MovieSeriesModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function seriesRemove(int $id) {
        MovieSeriesModel::where('id', $id)->delete();
    }

    public static function fileList(int $movie, int $series = 0) {
        return MovieFileModel::where('movie_id', $movie)
            ->where('series_id', $series)
            ->orderBy('id', 'asc')
            ->page();
    }

    protected static function afterSave(int $id, array $data) {
        if (isset($data['files'])) {
            foreach ($data['files'] as $item) {
                $item = Validator::filter($item, [
                    'id' => 'int',
                    'file_type' => 'int:0,127',
                    'file' => 'required|string:0,255',
                ]);
                $item['movie_id'] = $id;
                static::fileSave($item);
            }
        }
    }

    public static function fileSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = MovieFileModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function fileRemove(int $id) {
        MovieFileModel::where('id', $id)->delete();
    }

    public static function scoreList(int $movie)
    {
        return MovieScoreModel::where('movie_id', $movie)->get();
    }

    public static function scoreSave(array $data)
    {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = MovieScoreModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function scoreRemove(int $id)
    {
        MovieScoreModel::where('id', $id)->delete();
    }

    public static function areaList()
    {
        return AreaModel::query()->get();
    }

    public static function areaSave(array $data)
    {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = AreaModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function areaRemove(int $id) {
        AreaModel::where('id', $id)->delete();
    }

    public static function getEdit(int $id) {
        $model = self::get($id);
        $model->tags = TVRepository::tag()->getTags($id);
        return $model;
    }

    public static function search(string $keywords = '', int $category = 0, int $area = 0,
                                  int $age = 0)
    {
        return static::query()->with('category', 'area')
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, static::$searchKeys, true, '', $keywords);
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->when($area > 0, function ($query) use ($area) {
                $query->where('area_id', $area);
            })->when($age > 0, function ($query) use ($age) {
                $query->where('age', $age);
            })->select(self::MOVIE_PAGE_FILED)->page();
    }

    public static function getFull(int $id) {
        $model = static::get($id);
        $_ = $model->category;
        $_ = $model->area;
        $model->tags = TVRepository::tag()->getTags($id);
        if ($model->series_count > 1) {
            $model->series = self::seriesFull($id);
        } else {
            $model->files = MovieFileModel::where('movie_id', $id)->get();
        }
        return $model;
    }

    public static function seriesFull(int $movie) {
        return MovieSeriesModel::with('files')->where('movie_id', $movie)
            ->orderBy('episode', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public static function suggestion(string $keywords) {
        return MovieModel::when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['title'], false, '', $keywords);
        })->limit(4)->asArray()->get('id', 'title');
    }

    public static function recommend() {
        return static::query()->limit(6)->get(self::MOVIE_PAGE_FILED);
    }

    public static function download(int $id): string {
        $model = MovieFileModel::where('id', $id)->first();
        if (empty($model) || $model->file_type > 0) {
            throw new Exception('文件不存在');
        }
        return $model->file;
    }


    protected static function query(): SqlBuilder
    {
        return MovieModel::query();
    }



    protected static function createNew(): Model
    {
        return new MovieModel();
    }
}
