<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Zodream\Helpers\Json;

/**
 * Class ProjectModel
 * @package Module\Document\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property string $environment
 * @property integer $status
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProjectModel extends Model {

    const STATUS_PUBLIC = 0; // 公开
    const STATUS_PRIVATE = 1; // 私有

    public static function tableName() {
        return 'doc_project';
    }


    protected function rules() {
        return [
            'user_id' => 'required|int',
            'name' => 'required|string:0,35',
            'description' => 'string:0,255',
            'environment' => '',
            'status' => 'int:0,9',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => '项目名称',
            'description' => '项目描述',
            'environment' => '环境',
            'status' => '状态',
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
        if (!is_array($data)) {
            $this->__attributes['environment'] = $data;
            return;
        }
        if (!isset($data['name'])) {
            $data['name'] = [];
        }
        $env = [];
        foreach ($data['name'] as $key => $item) {
            if (empty($item)) {
                continue;
            }
            $env[] = [
                'name' => $item,
                'title' => $data['title'][$key],
                'domain' => $data['domain'][$key]
            ];
        }
        if (empty($env)) {
            $this->setError('environment', '请至少填写一个环境域名');
            return;
        }
        $this->__attributes['environment'] = Json::encode($env);
    }

    public function canRead() {
        return $this->status != self::STATUS_PRIVATE
            || $this->user_id == auth()->id();
    }
}