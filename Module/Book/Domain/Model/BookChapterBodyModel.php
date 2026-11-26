<?php
declare(strict_types=1);
namespace Module\Book\Domain\Model;

use Module\Book\Domain\Entities\ChapterBodyEntity;
use Zodream\Helpers\Html;

/**
 * Class BookChapterModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $content
 */
class BookChapterBodyModel extends ChapterBodyEntity {

    public function getHtmlAttribute() {
        return Html::fromText($this->content);
    }


}