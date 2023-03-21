<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Template\Domain\Entities\SiteEntity;

/**
 * Class SiteModel
 * @package Module\Template\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property string $domain
 * @property integer $theme_id
 * @property integer $default_page_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class SiteModel extends SiteEntity {

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}