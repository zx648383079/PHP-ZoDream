<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;
use Zodream\Database\Model\Query;
use Zodream\Helpers\Time;


/**
 * Class LogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $ip
 * @property string $user_agent
 * @property string $hostname
 * @property string $pathname
 * @property string $queries
 * @property string $referrer_hostname
 * @property string $referrer_pathname
 * @property string $method
 * @property integer $status_code
 * @property integer $user_id
 * @property string $session_id
 * @property string $latitude
 * @property string $longitude
 * @property integer $created_at
 */
class LogModel extends Model {

    public static function tableName(): string {
        return 'ctr_log';
    }

    protected function rules(): array {
        return [
            'ip' => 'required|string:0,120',
            'hostname' => 'string:0,100',
            'pathname' => 'string:0,255',
            'queries' => 'string:0,255',
            'referrer_hostname' => 'string:0,100',
            'referrer_pathname' => 'string:0,255',
            'method' => 'string:0,10',
            'status_code' => 'int',
            'user_agent' => 'string',
            'user_id' => 'int',
            'session_id' => 'string:0,32',
            'latitude' => 'string:0,30',
            'longitude' => 'string:0,30',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'ip' => 'Ip',
            'hostname' => 'Hostname',
            'pathname' => 'Pathname',
            'queries' => 'Queries',
            'referrer_hostname' => 'Referrer Hostname',
            'referrer_pathname' => 'Referrer Pathname',
            'method' => 'Method',
            'status_code' => 'Status Code',
            'user_agent' => 'User Agent',
            'user_id' => 'User Id',
            'session_id' => 'Session Id',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'created_at' => 'Created At',
        ];
    }

    public static function addLog() {
        $request = app('request');
        $os = $request->os();
        $browser = $request->browser();
        $model = new static;
        $model->ip = $request->ip();
        $model->browser = $browser[0];
        $model->browser_version = $browser[1];
        $model->os = $os[0];
        $model->os_version = $os[1];
        $model->referrer = $request->referrer();
        $model->url = url()->current();
        $model->session_id = session()->id();
        $model->user_agent = $request->server('HTTP_USER_AGENT', '-');
        $model->created_at = Time::format();
        return $model->save();
    }

    /**
     * 获取每天的状态
     * @param array|string $where
     * @return array
     */
    public static function getDayStatus($where = null) {
        return self::getStatus($where, 'DAYOFMONTH', 31);
    }

    /**
     * 获取每小时的状态
     * @param array|string $where
     * @return array
     */
    public static function getHourStatus($where = null) {
        return self::getStatus($where, 'HOUR', 24);
    }

    public static function getWeekStatus($where = null) {
        return self::getStatus($where, 'DAYOFWEEK', 7);
    }

    public static function getMonthStatus($where = null) {
        return self::getStatus($where, 'MONTH', 12);
    }

    public static function getYearStatus($where = null) {
        return self::getStatus($where, 'YEAR');
    }

    /**
     * 获取IP最后访问时间及来路
     * @return Query
     */
    public static function getLastQuery() {
        return LogModel::query()->select('ip, MAX(created_at) as created_at, referrer')
            ->groupBy(1)->orderBy('2 DESC');
    }

    /**
     * 获取所有的月份
     * @param string|array $where
     * @return array
     */
    public static function geAllMonth($where = null) {
        return static::query()->load([
            'select' => 'YEAR(created_at) as year, MONTH(created_at) as month, DAYOFMONTH(created_at) as day, COUNT(*) as count,COUNT(DISTINCT ip) as countIp',
            'where' => $where,
            'groupBy' => '1,2,3',
            'orderBy' => '1,2,3',
            'limit' => 30
        ])->all();
    }

    /**
     * 获取前三十名访问的网址
     * @param array|string $where
     * @return mixed
     */
    public static function geTopUrl($where = null) {
        return static::query()->load([
            'select' => 'url, COUNT(*) as count',
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => '2 DESC',
            'limit' => 30
        ])->all();
    }

    /**
     * 获取前三十名访问者的IP
     * @param array|string $where
     * @return mixed
     * @throws \Exception
     */
    public static function geTopIp($where = null) {
        return static::query()->load([
            'select' => 'ip, COUNT(*) as count',
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => '2 DESC',
            'limit' => 30
        ])->all();
    }

    /**
     * 获取前三十名访问者浏览器
     * @param array|string $where
     * @return mixed
     * @throws \Exception
     */
    public static function geTopBrowser($where = null) {
        return static::query()->load([
            'select' => 'browser, COUNT(*) as count',
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => '2 DESC',
            'limit' => 30
        ])->all();
    }

    /**
     * 获取前三十名访问者系统
     * @param array|string $where
     * @return mixed
     * @throws \Exception
     */
    public static function geTopOs($where = null) {
        return static::query()->load([
            'select' => 'os, COUNT(*) as count',
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => '2 DESC',
            'limit' => 30
        ])->all();
    }

    /**
     * 获取前三十名访问者国家
     * @param array|string $where
     * @return mixed
     * @throws \Exception
     */
    public static function geTopCountry($where = null) {
        return static::query()->load([
            'select' => 'RIGHT(ip,INSTR(REVERSE(ip),\".\")-1) as country, COUNT(*) as count',
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => '2 DESC',
            'limit' => 30
        ])->all();
    }

    /**
     * 获取前三十名搜索引擎来源
     * @param array|string $where
     * @return mixed
     */
    public static function getTopSearch($where = null) {
        if (empty($where)) {
            $where = [];
        }
        if (!is_array($where)) {
            $where = (array)$where;
        }
        $where[] = "(INSTR(referrer,'?q=') OR INSTR(referrer, '?wd=') OR INSTR(referrer,'?p=') OR INSTR(referrer,'?query=') OR INSTR(referrer,'?qkw=') OR INSTR(referrer,'?search=') OR INSTR(referrer,'?qr=') OR INSTR(referrer,'?string='))";
        $data = static::select('referrer,COUNT(*) as count')->load([
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => '2 DESC',
            'limit' => 30
        ])->all();
        $args = [];
        $urls = [];
        foreach ($data as $item) {
            if(preg_match('#//([\w\.]+?)/.*?\?[a-z]+=([^&]+)#i', $item['referrer'], $match)) {
                if (!array_key_exists($match[1], $urls)) {
                    $urls[$match[1]] = 0;
                }
                $count = intval($item['count']);
                $urls[$match[1]] += $count;
                $tags = explode(' ', urldecode($match[2]));
                if (!array_key_exists($match[2], $args)) {
                    $args[$match[2]] = 0;
                }
                $args[$match[2]] += $count;
                if (count($tags) == 1) {
                    continue;
                }
                foreach ($tags as $tag) {
                    if (empty($tag)) {
                        continue;
                    }
                    if (!array_key_exists($tag, $args)) {
                        $args[$tag] = 0;
                    }
                    $args[$tag] += $count;
                }
            }
        }
        arsort($args);
        arsort($urls);
        return [
            $args,
            $urls
        ];
    }

    /**
     * 获取前三十名外链
     * @param array|string $where
     * @return mixed
     * @throws \Exception
     */
    public static function getTopReferer($where = null) {
        if (empty($where)) {
            $where = [];
        }
        if (!is_array($where)) {
            $where = (array)$where;
        }
        $allUrls = static::where($where)->select('url')->all();
        $where[] = 'referrer NOT LIKE "%'.app('request')->host().'%"';
        $args = static::select('referrer,COUNT(*) as count')->load([
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => '2 DESC',
            'limit' => 20
        ])->all();
        $urls = static::where('url')->load([
            'where' => $where
        ])->all();
        return [
            $args,
            $urls,
            $allUrls
        ];
    }

    /**
     * @param $where
     * @param string $type
     * @param null $length
     * @return array
     */
    public static function getStatus($where = null, $type = 'DAYOFMONTH', $length = null) {
        $flowCount = [];
        $i = 0;
        if (empty($length)) {
            $i = 2015;
            $length = date('Y') + 1;
        } else if ($length != 24) {
            $i = 1;
            $length ++;
        }
        for (; $i < $length; $i++) {
            $flowCount[$i] = [
                0, // PV总计
                0,  // UV
                0   //IP
            ];
        }
        $args = static::select($type.'(created_at) as d, COUNT(*) as c')->load([
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => 1
        ])->all();
        $max = 0;
        foreach ($args as $item) {
            if (!array_key_exists($item['d'], $flowCount)) {
                continue;
            }
            $flowCount[$item['d']][0] = intval($item['c']);
            if ($item['c'] > $max) {
                $max = $item['c'];
            }
        }

        $uvs = $ips = static::select($type.'(create_at) as d, session')->load([
            'where' => $where,
            'groupBy' => '1,2',
            'orderBy' => 1
        ])->all();
        foreach ($uvs as $item) {
            if (!array_key_exists($item['d'], $flowCount)) {
                continue;
            }
            $flowCount[$item['d']][1] ++;
        }

        $ips = static::select($type.'(created_at) as d, ip')->load([
            'where' => $where,
            'groupBy' => '1,2',
            'orderBy' => 1
        ])->all();
        foreach ($ips as $item) {
            if (!array_key_exists($item['d'], $flowCount)) {
                continue;
            }
            $flowCount[$item['d']][2] ++;
        }
        return [
            $flowCount,
            $max,
            $args,
            $ips
        ];
    }

}
