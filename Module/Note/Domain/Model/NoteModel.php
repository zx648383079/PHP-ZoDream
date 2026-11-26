<?php
declare(strict_types=1);
namespace Module\Note\Domain\Model;

use Module\Note\Domain\Entities\NoteEntity;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Support\Html;

/**
 * Class NoteModel
 * @property integer $id
 * @property string $content
 * @property integer $user_id
 * @property integer $topic_id
 * @property string $extra_rule
 * @property string $properties
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class NoteModel extends NoteEntity {

    protected array $append = ['html'];

    public function getHtmlAttribute() {
	    $args = explode("\n", str_replace(["\t", ' '], ['    ', '&nbsp;'], strip_tags($this->content)));
	    return implode('', array_map(function ($item) {
	        return Html::p($item);
        }, $args));
    }

    public function getDateAttribute() {
        return Time::isTimeAgo($this->getAttributeSource('created_at'), 30 * 86400);
    }

    public function getEditableAttribute() {
        if (auth()->guest()) {
            return false;
        }
        return auth()->id() === $this->user_id;
    }

}