<?php
namespace Module\Shop\Domain\Model\Advertisement;

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
        return 'shop_ad';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'position_id' => 'required|int',
            'type' => 'int:0,9',
            'url' => 'required|string:0,255',
            'content' => 'required|string:0,255',
            'start_at' => 'int',
            'end_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'position_id' => 'Position Id',
            'type' => 'Type',
            'url' => 'Url',
            'content' => 'Content',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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