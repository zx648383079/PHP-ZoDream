<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;

/**
 * Class PageModel
 * @package Module\Template
 * @property integer $id
 * @property integer $site_id
 * @property integer $type
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property string $settings
 * @property integer $position
 * @property integer $theme_page_id
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property SiteModel $site
 */
class PageModel extends Model {

    const TYPE_NONE = 0;

    const TYPE_WAP = 1;

    public static function tableName() {
        return 'tpl_page';
    }

    protected function rules() {
        return [
            'site_id' => 'required|int',
            'type' => 'int:0,9',
            'name' => 'required|string:0,100',
            'title' => 'string:0,200',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
            'description' => 'string:0,255',
            'settings' => '',
            'theme_page_id' => 'required|int',
            'position' => 'int:0,99',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'site_id' => 'Site Id',
            'type' => 'Type',
            'name' => 'Name',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'description' => 'Description',
            'theme_page_id' => 'Theme Page Id',
            'settings' => 'Settings',
            'position' => 'Position',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function page() {
        return $this->hasOne(ThemePageModel::class, 'id', 'theme_page_id');
    }

    public function site() {
        return $this->hasOne(SiteModel::class, 'id', 'site_id');
    }

    public function weights() {
        return $this->hasMany(PageWeightModel::class, 'page_id', 'id');
    }

    /**
     * @param $id
     * @param int $site
     * @return PageModel
     * @throws \Exception
     */
    public static function findByIdOrSite($id, $site = 0) {
        if ($id > 0) {
            return self::find($id);
        }
        return self::where('site_id', $site)->orderBy('position', 'asc')
            ->orderBy('id', 'asc')->first();
    }
}