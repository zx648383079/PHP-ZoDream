<?php
namespace Domain\Model\Advertisement;

use Domain\Model\Model;
use Zodream\Infrastructure\Support\Html;

/**
 * Class AdModel
 * @package Domain\Model\Advertisement
 * @property integer $id
 * @property integer $position_id
 * @property string $name
 * @property integer $type
 * @property string $url
 * @property string $content
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $update_at
 * @property integer $create_at
 */
class AdModel extends Model {
    const TEXT = 0;
    const IMG = 1;
    const HTML = 2;
    const VIDEO = 3;

    public static function tableName() {
        return 'ad';
    }

    public function getHtml() {
        switch ($this->type) {
            case self::TEXT:
                return Html::a(htmlspecialchars($this->content), $this->url);
            case self::IMG:
                return Html::a(Html::img($this->content), $this->url);
            case self::VIDEO:
                return Html::a(Html::tag('embed', '', ['src' => $this->content]), $this->url);
            case self::HTML:
            default:
                return htmlspecialchars_decode($this->content);
        }
    }

    /**
     * @param integer $id
     * @return AdModel
     */
    public static function getAd($id) {
        return '';
    }

    /**
     * @param integer $id
     * @return AdModel[]
     */
    public static function getAds($id) {
        return [];
    }

    public function __toString() {
        return $this->getHtml();
    }
}