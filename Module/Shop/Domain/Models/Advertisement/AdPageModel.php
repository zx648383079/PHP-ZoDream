<?php
namespace Module\Shop\Domain\Models\Advertisement;

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
class AdPageModel extends AdModel {

    protected array $append = ['position'];
}