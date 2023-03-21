<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Module\Template\Domain\Entities\SitePageWeightEntity;


/**
 * Class PageWeightModel
 * @package Module\Template
 * @property integer $id
 * @property integer $page_id
 * @property integer $site_id
 * @property integer $weight_id
 * @property integer $parent_id
 * @property integer $parent_index
 * @property integer $position
 *
 */
class SitePageWeightModel extends SitePageWeightEntity {

}