<?php
namespace Module\Legwork\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Json;

/**
 * Class ServiceModel
 * @package Module\Legwork\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $cat_id
 * @property string $thumb
 * @property string $brief
 * @property float $price
 * @property string $content
 * @property string $form
 * @property integer $created_at
 * @property integer $updated_at
 */
class ServiceSimpleModel extends ServiceModel {

   public static function query() {
       return parent::query()->select('id',
           'name', 'brief', 'price', 'cat_id', 'user_id');
   }
}