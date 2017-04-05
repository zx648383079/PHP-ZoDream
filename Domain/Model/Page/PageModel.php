<?php
namespace Domain\Model\Page;

use Domain\Model\Model;

/**
 * Class PageModel
 * @package Domain\Model\Page
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $template
 * @property integer $update_at
 * @property integer $create_at
 */
class PageModel extends Model {
    public static function tableName() {
        return 'page';
    }

    public function generate() {

    }
}