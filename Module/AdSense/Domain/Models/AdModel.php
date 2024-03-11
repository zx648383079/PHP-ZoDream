<?php
namespace Module\AdSense\Domain\Models;

use Module\AdSense\Domain\Entities\AdEntity;
use Module\AdSense\Domain\Entities\AdPositionEntity;
use Module\AdSense\Domain\Repositories\AdRepository;

/**
 * @property integer $id
 * @property string $name
 * @property integer $position_id
 * @property integer $type
 * @property string $url
 * @property string $content
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class AdModel extends AdEntity {
    public function position() {
        return $this->hasOne(AdPositionEntity::class, 'id', 'position_id');
    }

    public function setStartAtAttribute($value) {
        if (!is_numeric($value)) {
            $value = strtotime($value);
        }
        $this->setAttributeToSource('start_at', $value < 0 ? 0 : $value);
    }

    public function setEndAtAttribute($value) {
        if (!is_numeric($value)) {
            $value = strtotime($value);
        }
        $this->setAttributeToSource('end_at', $value < 0 ? 0 : $value);
    }

    public function getStartAtAttribute() {
        return $this->formatTimeAttribute('start_at');
    }

    public function getEndAtAttribute() {
        return $this->formatTimeAttribute('end_at');
    }

    public function getContentAttribute() {
        $content = $this->getAttributeSource('content');
        return !empty($content) && $this->type == AdRepository::TYPE_IMAGE ?
            url()->asset($content) : $content;
    }
}