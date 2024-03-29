<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Domain\Model\ModelHelper;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;
use Domain\Repositories\FileRepository;

/**
 * Class ProjectModel
 * @package Module\Document\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $cat_id
 * @property string $name
 * @property string $cover
 * @property string $description
 * @property string $environment
 * @property integer $status
 * @property integer $type
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProjectModel extends Model {

    const STATUS_PUBLIC = 0; // 公开
    const STATUS_PRIVATE = 1; // 私有

    const TYPE_NONE = 0;
    const TYPE_API = 1;

    public static function tableName(): string {
        return 'doc_project';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'cat_id' => 'int',
            'name' => 'required|string:0,35',
            'description' => 'string:0,255',
            'cover' => 'string:0,255',
            'environment' => '',
            'status' => 'int:0,9',
            'type' => 'int:0,9',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'cat_id' => 'Cat Id',
            'name' => '项目名称',
            'description' => '项目描述',
            'cover' => '项目封面',
            'environment' => '环境',
            'status' => '状态',
            'type' => '类型',
            'deleted_at' => '删除时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getEnvironmentAttribute() {
        return empty($this->__attributes['environment']) ? []
            : Json::decode($this->__attributes['environment']);
    }

    public function setEnvironmentAttribute($data) {
        if ($this->type != self::TYPE_API) {
            return;
        }
        if (!is_array($data)) {
            $this->__attributes['environment'] = $data;
            return;
        }
        if (Arr::isAssoc($data)) {
            $data = ModelHelper::formArr($data, '', function (array $item) {
                return !empty($item['name']);
            });
        } else {
            $data = array_filter($data, function (array $item) {
                return !empty($item['name']);
            });
        }
        if (empty($data)) {
            $this->setError('environment', '请至少填写一个环境域名');
            return;
        }
        $this->__attributes['environment'] = Json::encode($data);
    }

    public function getCoverAttribute() {
        return FileRepository::formatImage($this->getAttributeSource('cover'));
    }

    public function canRead() {
        return $this->status != self::STATUS_PRIVATE
            || $this->user_id == auth()->id();
    }
}