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

    protected static function query(): SqlBuilder
    {
        return MusicModel::query();
    }

    protected static function createNew(): Model
    {
        return new MusicModel();
    }
}
