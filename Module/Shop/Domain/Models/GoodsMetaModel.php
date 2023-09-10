<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Models;

use Domain\Concerns\TableMeta;
use Domain\Model\Model;

/**
 * Class GoodsMetaModel
 * @package Module\Shop\Domain\Models
 * @property integer $id
 * @property integer $goods_id
 * @property string $name
 * @property string $content
 */
class GoodsMetaModel extends Model {

    use TableMeta;

    protected static string $idKey = 'goods_id';

    protected static array $defaultItems = [
        'seo_link' => '', // 优雅链接
        'seo_title' => '', // 'seo 优化标题',
        'seo_description' => '', // 'seo 优化描述',
    ];

    public static function tableName(): string {
        return 'shop_goods_meta';
    }

    public function rules(): array {
        return [
            'goods_id' => 'required|int',
            'name' => 'required|string:0,100',
            'content' => 'required',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'name' => 'Name',
            'content' => 'Content',
        ];
    }
}