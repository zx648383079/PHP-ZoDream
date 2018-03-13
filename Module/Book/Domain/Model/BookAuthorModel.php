<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;
use Zodream\Service\Routing\Url;

/**
 * Class BookAuthorModel
 * @package Module\Book\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $avatar
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookAuthorModel extends Model {

    public static function tableName() {
        return 'book_author';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'avatar' => 'string:0,200',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUrlAttribute() {
        return Url::to('./author', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return Url::to('./mobile/author', ['id' => $this->id]);
    }
}