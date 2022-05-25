<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Module\OnlineTV\Domain\Models\AreaModel;
use Module\OnlineTV\Domain\Models\MovieFileModel;
use Module\OnlineTV\Domain\Models\MovieModel;
use Module\OnlineTV\Domain\Models\MovieScoreModel;
use Module\OnlineTV\Domain\Models\MovieSeriesModel;
use Zodream\Database\Contracts\SqlBuilder;

final class MovieRepository extends CRUDRepository {

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

    public static function areaRemove(int $id)
    {
        AreaModel::where('id', $id)->delete();
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
