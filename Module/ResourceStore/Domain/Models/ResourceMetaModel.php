<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Models;

use Domain\Concerns\TableMeta;
use Domain\Model\Model;

/**
 * @property integer $id
 * @property integer $res_id
 * @property string $name
 * @property string $content
 */
class ResourceMetaModel extends Model {
    use TableMeta;

    protected static string $idKey = 'res_id';
    protected static array $defaultItems = [
        'preview_file' => '', // 预览文件地址
        'file_catalog' => '', // 文件目录缓存
    ];

    public static function tableName(): string {
        return 'res_resource_meta';
    }

    protected function rules(): array {
        return [
            'res_id' => 'required|int',
            'name' => 'required|string:0,40',
            'content' => 'required',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'res_id' => 'Res Id',
            'name' => 'Name',
            'content' => 'Content',
        ];
    }
}