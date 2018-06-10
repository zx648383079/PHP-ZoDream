<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class ArticleModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property integer $cat_id
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property string $brief
 * @property string $url
 * @property string $file
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class ArticleModel extends Model {
    public static function tableName() {
        return 'article';
    }

    protected function rules() {
        return [
            'cat_id' => 'required|int',
            'title' => 'required|string:0,100',
            'keywords' => 'string:0,200',
            'thumb' => 'string:0,200',
            'description' => 'string:0,200',
            'brief' => 'string:0,200',
            'url' => 'string:0,200',
            'file' => 'string:0,200',
            'content' => 'required',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'cat_id' => 'Cat Id',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'description' => 'Description',
            'brief' => 'Brief',
            'url' => 'Url',
            'file' => 'File',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function category() {
        return $this->hasOne(ArticleCategoryModel::class, 'id', 'cat_id');
    }

    public static function getHelps(){
        $data = ArticleCategoryModel::where('parent_id', 2)->all();
        foreach ($data as $item) {
            $item->children = static::where('cat_id', $item->id)->all();
        }
        return $data;
    }
}