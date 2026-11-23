<?php
namespace Module\Book\Domain\Model;

use Module\Book\Domain\Entities\AuthorEntity;


/**
 * Class BookAuthorModel
 * @package Module\Book\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $avatar
 * @property string $description
 * @property integer $user_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookAuthorModel extends AuthorEntity {



    public function getUrlAttribute() {
        return url('./author', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return url('./mobile/author', ['id' => $this->id]);
    }

    /**
     * 新建
     * @param $name
     * @return static
     */
    public static function findOrNewByName(string $name) {
        $name = trim($name);
        if (empty($name)) {
            $name = '未知';
        }
        $model = static::where('name', $name)->first();
        if (!empty($model)) {
            return $model;
        }
        return static::create([
            'name' => $name
        ]);
    }

    public static function findIdByName(string $name) {
        return static::findOrNewByName($name)->id;
    }
}