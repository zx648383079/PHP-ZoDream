<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $happen_day
 * @property integer $blog_id
 * @property integer $click_count
 */
class BlogClickLogModel extends Model {
    public static function tableName(): string {
        return 'blog_click_log';
    }

    protected function rules(): array {
        return [
            'happen_day' => 'required|string',
            'blog_id' => 'required|int',
            'click_count' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'happen_day' => 'Happen Day',
            'blog_id' => 'Blog Id',
            'click_count' => 'Click Count',
        ];
    }
}