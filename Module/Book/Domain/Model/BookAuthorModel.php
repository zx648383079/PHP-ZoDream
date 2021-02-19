<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;


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
class BookAuthorModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_ALLOW = 1;
    const STATUS_DISALLOW = 2;

    public static function tableName() {
        return 'book_author';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'avatar' => 'string:0,200',
            'description' => 'string:0,200',
            'user_id' => 'int',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '笔名',
            'avatar' => '头像',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

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
    public static function findOrNewByName($name) {
        $name = trim($name);
        if (empty($name)) {
            $name = '未知';
        }
        $model = static::where('name', $name)->one();
        if (!empty($model)) {
            return $model;
        }
        return static::create([
            'name' => $name
        ]);
    }

    public static function findIdByName($name) {
        return static::findOrNewByName($name)->id;
    }
}