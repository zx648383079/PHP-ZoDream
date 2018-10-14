<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;

/**
 * Class SiteModel
 * @package Module\Template\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property string $domain
 * @property integer $created_at
 * @property integer $updated_at
 */
class SiteModel extends Model {
    public static function tableName() {
        return 'tpl_site';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'title' => 'string:0,200',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
            'description' => 'string:0,255',
            'domain' => 'string:0,50',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'description' => 'Description',
            'domain' => 'Domain',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}