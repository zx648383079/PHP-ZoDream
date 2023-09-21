<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;
use Module\CMS\Domain\Entities\LinkageDataEntity;

/**
 * Class LinkageModel
 * @package Module\CMS\Domain\Model
 * @property integer $id
 * @property integer $linkage_id
 * @property string $name
 * @property integer $parent_id
 * @property integer $position
 * @property string $full_name
 * @property string $description
 * @property string $thumb
 */
class LinkageDataModel extends LinkageDataEntity {

    public function createFullName() {
        if ($this->parent_id < 1) {
            $this->full_name = $this->name;
            return $this;
        }
        $this->full_name = sprintf('%s %s', static::where('id', $this->parent_id)->value('full_name'), $this->name);
        return $this;
    }
}