<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Exception;
use Module\OnlineTV\Domain\Models\MusicFileModel;
use Module\OnlineTV\Domain\Models\MusicModel;
use Zodream\Database\Contracts\SqlBuilder;

final class MusicRepository extends CRUDRepository {

    public static function fileList(int $music)
    {
        return MusicFileModel::where('music_id', $music)->get();
    }

    public static function fileSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = MusicFileModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function fileRemove(int $id) {
        MusicFileModel::where('id', $id)->delete();
    }

    public static function search(string $keywords, array|int $id = 0) {
        if ($id === 0) {
            return static::query()->with('files')->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, static::$searchKeys, true, '', $keywords);
            })->page();
        }
        return SearchModel::searchOption(
            static::query()->with('files'),
            ['name'],
            $keywords,
            compact('id')
        );
    }

    public static function suggestion(string $keywords) {
        return MusicModel::when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], false, '', $keywords);
        })->limit(4)->asArray()->get('id', 'name');
    }

    public static function download(int $id): string {
        $model = MusicFileModel::where('id', $id)->first();
        if (empty($model) || $model->file_type > 0) {
            throw new Exception('文件不存在');
        }
        return $model->file;
    }

    protected static function query(): SqlBuilder
    {
        return MusicModel::query();
    }



    protected static function createNew(): Model
    {
        return new MusicModel();
    }
}
