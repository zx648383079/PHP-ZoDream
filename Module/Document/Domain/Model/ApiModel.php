<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Zodream\Html\Tree;
use Zodream\Http\Uri;

/**
 * Class ApiModel
 * @package Module\Document\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $method
 * @property string $uri
 * @property integer $project_id
 * @property string $description
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ApiModel extends Model {

    const PRE_STORE_KEY = 'pre_store_api';

    public $method_list = [
        'GET', 'POST', 'PUT', 'DELETE', 'OPTION'
    ];

    public static function tableName() {
        return 'doc_api';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,35',
            'method' => 'required|string:0,10',
            'uri' => 'string:0,255',
            'project_id' => 'required|int',
            'description' => 'string:0,255',
            'parent_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '接口名称',
            'method' => '请求类型',
            'uri' => '接口路径',
            'project_id' => '项目',
            'description' => '接口描述',
            'parent_id' => '上级',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getUri($domain) {
        return sprintf('%s/%s', trim($domain, '/'), trim($this->uri, '/'));
    }

    public  static function getTree($project_id) {
        $data = self::where('project_id', $project_id)->select('id', 'name', 'parent_id')->asArray()->all();
        return (new Tree($data))->makeTree();
    }

    /**
     * 预存储字段
     * @param $id
     * @throws \Exception
     */
    public static function preStore($id) {
        if ($id instanceof FieldModel) {
            return static::preField($id);
        }
        $id = intval($id);
        if ($id < 1) {
            return;
        }
        $data = self::getStore();
        if (in_array($id, $data)) {
            return;
        }
        $data[] = $id;
        session()->set(self::PRE_STORE_KEY, $data);
    }

    public static function preField(FieldModel $model) {
        self::preStore($model->id);
        if (empty($model->children)) {
            return;
        }
        foreach ($model->children as $item) {
            static::preField($item);
        }
    }

    /**
     * 获取
     * return array
     */
    public static function getStore() {
        $data = session(self::PRE_STORE_KEY);
        return empty($data) ? [] : $data;
    }

    /**
     * 删除
     * @return array
     * @throws \Exception
     */
    public static function clearStore() {
        $data = self::getStore();
        session()->delete(self::PRE_STORE_KEY);
        return $data;
    }
}