<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Time;
use Zodream\Service\Factory;

/**
 * Class LogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $ip
 * @property string $browser
 * @property string $browser_version
 * @property string $os
 * @property string $os_version
 * @property string $url
 * @property string $referrer
 * @property string $user_agent
 * @property string $country
 * @property string $region
 * @property string $city
 * @property integer $user_id
 * @property string $session_id
 * @property string $latitude
 * @property string $longitude
 * @property integer $created_at
 */
class LogModel extends Model {

    public static function tableName() {
        return 'ctr_log';
    }

    protected function rules() {
        return [
            'ip' => 'required|string:0,120',
            'browser' => 'string:0,40',
            'browser_version' => 'string:0,20',
            'os' => 'string:0,20',
            'os_version' => 'string:0,20',
            'url' => 'string:0,255',
            'referrer' => 'string:0,255',
            'user_agent' => 'string:0,255',
            'country' => 'string:0,45',
            'region' => 'string:0,45',
            'city' => 'string:0,45',
            'user_id' => 'int',
            'session_id' => 'string:0,30',
            'latitude' => 'string:0,30',
            'longitude' => 'string:0,30',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'ip' => 'Ip',
            'browser' => 'Browser',
            'browser_version' => 'Browser Version',
            'os' => 'Os',
            'os_version' => 'Os Version',
            'url' => 'Url',
            'referrer' => 'Referrer',
            'user_agent' => 'User Agent',
            'country' => 'Country',
            'region' => 'Region',
            'city' => 'City',
            'user_id' => 'User Id',
            'session_id' => 'Session Id',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'created_at' => 'Created At',
        ];
    }

    public static function addLog() {
        $os = app('request')->os();
        $browser = app('request')->browser();
        $model = new static;
        $model->ip = app('request')->ip();
        $model->browser = $browser[0];
        $model->browser_version = $browser[1];
        $model->os = $os[0];
        $model->os_version = $os[1];
        $model->referer = Url::referrer();
        $model->url = url()->current();
        $model->session = Factory::session()->id();
        $model->agent = app('request')->server('HTTP_USER_AGENT', '-');
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
     * @param string|array $where
     * @return array
     */
    public static function getLast($where = null) {
        return static::query()->load([
            'select' => 'ip, MAX(create_at) as create_at, referer',
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => '2 DESC',
            'limit' => 15
        ])->all();
    }

    /**
     * 获取所有的月份
     * @param string|array $where
     * @return array
     */
    public static function geAllMonth($where = null) {
        return static::query()->load([
            'select' => 'YEAR(create_at) as year, MONTH(create_at) as month, DAYOFMONTH(create_at) as day, COUNT(*) as count,COUNT(DISTINCT ip) as countIp',
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
        $where[] = "(INSTR(referer,'?q=') OR INSTR(referer, '?wd=') OR INSTR(referer,'?p=') OR INSTR(referer,'?query=') OR INSTR(referer,'?qkw=') OR INSTR(referer,'?search=') OR INSTR(referer,'?qr=') OR INSTR(referer,'?string='))";
        $data = static::select('referer,COUNT(*) as count')->load([
            'where' => $where,
            'groupBy' => 1,
            'orderBy' => '2 DESC',
            'limit' => 30
        ])->all();
        $args = [];
        $urls = [];
        foreach ($data as $item) {
            if(preg_match('#//([\w\.]+?)/.*?\?[a-z]+=([^&]+)#i', $item['referer'], $match)) {
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
        $where[] = 'referer NOT LIKE "%'.url()->getHost().'%"';
        $args = static::select('referer,COUNT(*) as count')->load([
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
        $args = static::select($type.'(create_at) as d, COUNT(*) as c')->load([
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

        $ips = static::select($type.'(create_at) as d, ip')->load([
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
