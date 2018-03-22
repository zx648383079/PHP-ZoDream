<?php
namespace Module\Book\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/10/15
 * Time: 21:00
 */
use Domain\Model\Model;

/**
 * Class BookChapterModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $content
 */
class BookChapterBodyModel extends Model {
    public static function tableName() {
        return 'book_chapter_body';
    }

    protected function rules() {
        return [
            'content' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'content' => 'Content',
        ];
    }

    public function getHtmlAttribute() {
        $args = explode(PHP_EOL, $this->content);
        return implode('', array_map(function ($line) {
            return sprintf('<p>&nbsp;&nbsp;&nbsp;&nbsp;%s</p>', $line);
        }, $args));
    }


}