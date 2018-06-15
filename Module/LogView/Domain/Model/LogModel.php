<?php
namespace Module\LogView\Domain\Model;

use Domain\Model\Model;
use Zodream\Database\Model\Query;

/**
 * Class LogModel
 * @package Module\LogView\Domain\Model
 * @property integer $id
 * @property integer $file_id
 * @property string $date
 * @property string $time
 * @property string $s_sitename
 * @property string $s_computername
 * @property string $s_ip
 * @property string $cs_method
 * @property string $cs_uri_stem
 * @property string $cs_uri_query
 * @property integer $s_port
 * @property string $cs_username
 * @property string $c_ip
 * @property string $cs_user_agent
 * @property string $cs_version
 * @property string $cs_referer
 * @property string $cs_cookie
 * @property string $cs_host
 * @property integer $sc_status
 * @property integer $sc_substatus
 * @property integer $sc_win32_status
 * @property integer $sc_bytes
 * @property integer $cs_bytes
 * @property integer $time_taken
 */
class LogModel extends Model {

    public static function tableName() {
        return 'log_data';
    }

    protected function rules() {
        return [
            'id' => 'int',
            'file_id' => 'required|int',
            'date' => '',
            'time' => '',
            's_sitename' => 'string:0,30',
            's_computername' => 'string:0,30',
            's_ip' => 'string:0,120',
            'cs_method' => 'string:0,10',
            'cs_uri_stem' => 'string:0,255',
            'cs_uri_query' => 'string:0,255',
            's_port' => 'int',
            'cs_username' => 'string:0,40',
            'c_ip' => 'string:0,120',
            'cs_user_agent' => 'string:0,255',
            'cs_version' => 'string:0,20',
            'cs_referer' => 'string:0,255',
            'cs_cookie' => 'string:0,255',
            'cs_host' => 'string:0,255',
            'sc_status' => 'int',
            'sc_substatus' => 'int',
            'sc_win32_status' => 'int',
            'sc_bytes' => 'int',
            'cs_bytes' => 'int',
            'time_taken' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'file_id' => 'File Id',
            'date' => '日期',
            'time' => '时间',
            's_sitename' => '服务名称',
            's_computername' => '服务器名称',
            's_ip' => '服务器IP',
            'cs_method' => '方法',
            'cs_uri_stem' => 'URL资源',
            'cs_uri_query' => 'URL查询',
            's_port' => '服务器端口',
            'cs_username' => '用户名',
            'c_ip' => '客户端IP',
            'cs_user_agent' => '用户代理',
            'cs_version' => '协议版本',
            'cs_referer' => '引用网站',
            'cs_cookie' => 'Cookie',
            'cs_host' => '主机',
            'sc_status' => '协议状态',
            'sc_substatus' => '协议子状态',
            'sc_win32_status' => 'win32状态',
            'sc_bytes' => '发送的字节数',
            'cs_bytes' => '接收的字节数',
            'time_taken' => '所用时间',
        ];
    }

    public function scopeOfType(Query $query, $name, $operator, $value) {
        if (empty($name) || !$this->hasColumn($name)) {
            return $query;
        }
        if (in_array(strtolower($operator), [
            '=', '<', '>', '<=', '>=', '<>', '!=',
            'in', 'not in', 'is', 'is not',
            'like', 'like binary', 'not like', 'between', 'not between', 'ilike',
            '&', '|', '^', '<<', '>>',
            'rlike', 'regexp', 'not regexp',
            '~', '~*', '!~', '!~*', 'similar to',
            'not similar to'
        ])) {
            return $query->where($name, $operator, $value);
        }
        return $query;
    }

    public function scopeSortOrder(Query $query, $sort, $order) {
        if (empty($sort) || !$this->hasColumn($sort)) {
            return $query;
        }
        return $query->orderBy($sort, $order);
    }

    public function scopeCountByDate(Query $query, $format = '%Y%m%d', $as = 'day', $fields = 'COUNT(id) as count') {
        return $query->selectRaw(sprintf('DATE_FORMAT(`date`, \'%s\') as %s, %s', $format, $as, $fields))
            ->groupBy($as);
    }

    public function scopeCountByTime(Query $query, $format = '%H', $as = 'hour', $fields = 'COUNT(id) as count') {
        return $query->selectRaw(sprintf('DATE_FORMAT(`time`, \'%s\') as %s, %s', $format, $as, $fields))
            ->groupBy($as);
    }

    public static function getRoundLogs(array $log_list, $min = 0, $max = 24, $key = 'hour') {
        $args = [];
        foreach ($log_list as $item) {
            $index = intval($item[$key]);
            if (!isset($args[$index])) {
                $args[$index] = $item['count'];
                continue;
            }
            $args[$index] += $item['count'];
        }
        $data = [];
        for (; $min <= $max; $min ++) {
            $data[$min] = isset($args[$min]) ? $args[$min] : 0;
        }
        return $data;
    }
}