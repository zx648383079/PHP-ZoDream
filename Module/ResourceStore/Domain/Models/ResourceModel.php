<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Models;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Domain\Repositories\FileRepository;

/**
 * Class PostModel
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $thumb
 * @property string $content
 * @property integer $size
 * @property float $score
 * @property integer $user_id
 * @property integer $preview_type
 * @property integer $cat_id
 * @property integer $price
 * @property integer $is_commercial
 * @property integer $is_reprint
 * @property integer $comment_count
 * @property integer $view_count
 * @property integer $download_count
 * @property integer $updated_at
 * @property integer $created_at
 */
class ResourceModel extends Model {
    public static function tableName(): string {
        return 'res_resource';
    }

    protected function rules(): array {
        return [
            'title' => 'required|string:0,200',
            'description' => 'string:0,255',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
            'content' => 'required',
            'size' => 'int',
            'score' => 'numeric',
            'user_id' => 'required|int',
            'preview_type' => 'int:0,127',
            'cat_id' => 'required|int',
            'price' => 'int',
            'is_commercial' => 'int:0,127',
            'is_reprint' => 'int:0,127',
            'comment_count' => 'int',
            'view_count' => 'int',
            'download_count' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'title' => '标题',
            'description' => '简介',
            'keywords' => '关键字',
            'thumb' => '预览图',
            'content' => '内容',
            'file' => '文件',
            'user_id' => '作者',
            'cat_id' => '分类',
            'comment_count' => 'Comment Count',
            'view_count' => 'Click Count',
            'download_count' => 'Download Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getThumbAttribute() {
        return FileRepository::formatImage($this->getAttributeSource('thumb'));
    }

    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function saveIgnoreUpdate() {
        return $this->save() || $this->isNotChangedError();
    }
}