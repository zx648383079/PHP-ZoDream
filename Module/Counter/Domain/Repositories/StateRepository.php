<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Repositories;

use Module\Counter\Domain\Model\JumpLogModel;
use Module\Counter\Domain\Model\LogModel;
use Module\Counter\Domain\Model\StayTimeLogModel;
use Zodream\Html\Page;
use Zodream\Infrastructure\Support\UserAgent;
use Zodream\Database\Contracts\SqlBuilder;

class StateRepository {

    public static function getTimeInput(): array {
        $request = request();
        $start_at = $request->get('start_at', 'today');
        $end_at = $request->get('end_at');
        if ($start_at === 'today' || $start_at === 'yesterday' || $start_at === 'week' || $start_at === 'month') {
            return static::getTimeRange($start_at);
        }
        return [is_numeric($start_at) ? $start_at : strtotime($start_at),
            is_numeric($end_at) ? $end_at : strtotime($end_at)];
    }

    public static function getTimeRange(string $type): array {
        $time = strtotime(date('Y-m-d 00:00:00'));
        if ($type === 'today') {
            return [$time, $time + 86400];
        }
        if ($type === 'yesterday') {
            return [$time - 86400, $time];
        }
        if ($type === 'week') {
            return [$time - 6 * 85400, $time + 86400];
        }
        if ($type === 'month') {
            return [$time - 29 * 85400, $time + 86400];
        }
        return [0, 0];
    }

    public static function statisticsByTime(int $start_at, int $end_at): array {
        $pv = StayTimeLogModel::where('enter_at',  '>=', $start_at)
            ->where('enter_at',  '<', $end_at)->count();
        $uv = 0;//StayTimeLogModel::where('enter_at',  '>=', $start_at)
//            ->where('enter_at',  '<', $end_at)
//            ->groupBy('session_id')->count();
        $ip_count = 0;//StayTimeLogModel::where('enter_at',  '>=', $start_at)
//            ->where('enter_at',  '<', $end_at)
//            ->groupBy('ip')->count();
        $jump_count = JumpLogModel::where('created_at',  '>=', $start_at)
            ->where('created_at',  '<', $end_at)->count();
        $stay_time = (int)StayTimeLogModel::query()->where('enter_at',  '>=', $start_at)
            ->where('enter_at',  '<', $end_at)
            ->where('leave_at', '>', 0)
            ->avg('leave_at - enter_at');
        $request = request();
        $host = sprintf('%s://%s', $request->isSSL() ? 'https' : 'http', $request->host());
        $next_time = LogModel::query()->where('created_at',  '>=', $start_at)
            ->where('created_at',  '<', $end_at)
            ->where('referrer_hostname', $host)->count();
        return compact('pv', 'uv', 'ip_count', 'jump_count', 'stay_time', 'next_time');
    }

    public static function currentStay() {
        $items = StayTimeLogModel::query()->orderBy('id', SORT_DESC)->page();
        $items->map(function ($item) {
           return static::formatAgent($item);
        });
        return $items;
    }

    public static function allUrl(int $start_at, int $end_at) {
        $key = __CLASS__.__METHOD__.$start_at.$end_at;
        $items = cache()->getOrSet($key, function () use ($start_at, $end_at) {
            return self::mapGroups($start_at, $end_at, function ($url) {
                if (empty($url)) {
                    return false;
                }
                return $url;
            }, 'url', null, 'pathname');
        }, 60);
        return new Page($items);
    }

    public static function enterUrl(int $start_at, int $end_at) {
        $key = __CLASS__.__METHOD__.$start_at.$end_at;
        $items = cache()->getOrSet($key, function () use ($start_at, $end_at) {
            return self::mapGroups($start_at, $end_at, function ($url) {
                if (empty($url)) {
                    return false;
                }
                return $url;
            }, 'url', LogModel::query()
                ->where(function ($query) {
                    $query->where('referrer_hostname', '==', '')
                        ->orWhere('referrer_hostname', '!=', request()->host());
                }), 'pathname');
        }, 60);
        return new Page($items);
    }

    public static function domain(int $start_at, int $end_at) {
        return self::mapGroups($start_at, $end_at, function ($host) {
            if (empty($host)) {
                return;
            }
            if (str_starts_with($host, 'www.')) {
                return substr($host, 4);
            }
            return $host;
        }, 'host', null, 'hostname');
    }

    private static function isSearch(string $url): string|false {
        if (preg_match('/[\?&](q|wd|p|query|qkw|search|qr|string|keyword)\=([^&]*)/', $url, $match)) {
            return $match[2];
        }
        return false;
    }

    public static function searchKeywords(int $start_at, int $end_at) {
        return self::mapGroups($start_at, $end_at, function ($url) {
            if (empty($url)) {
                return;
            }
            $words = self::isSearch($url);
            if ($words === false || $words === '') {
                return;
            }
            return $words;
        }, 'words');
    }

    public static function searchEngine(int $start_at, int $end_at) {
        return self::mapGroups($start_at, $end_at, function ($url) {
            if (empty($url)) {
                return;
            }
            $words = self::isSearch($url);
            if ($words === false) {
                return;
            }
            $host = parse_url($url, PHP_URL_HOST);
            if (str_starts_with($host, 'www.')) {
                return substr($host, 4);
            }
            return $host;
        }, 'host');
    }

    public static function host(int $start_at, int $end_at) {
        return self::mapGroups($start_at, $end_at, function ($url) {
            if (empty($url)) {
                return;
            }
            $host = parse_url($url, PHP_URL_HOST);
            if (empty($host)) {
                return;
            }
            if (str_starts_with($host, 'www.')) {
                return substr($host, 4);
            }
            return $host;
        }, 'host');
    }

    public static function allSource(int $start_at, int $end_at) {
        return self::mapGroups($start_at, $end_at, function ($url) {
            if (empty($url)) {
                return '直接访问';
            }
            $host = parse_url($url, PHP_URL_HOST);
            if (empty($host)) {
                return '直接访问';
            }
            if (str_starts_with($host, 'www.')) {
                return substr($host, 4);
            }
            return $host;
        }, 'host', LogModel::query());
    }

    public static function getJumpCount(string $url): int {
        if (empty($url)) {
            return 0;
        }
        return JumpLogModel::query()->where('referrer', $url)
            ->count();
    }

    public static function getJumpScale(string $url, int $count): string {
        if (empty($url) || $count < 1) {
            return '0%';
        }
        $total = JumpLogModel::query()->whereNotNull('referrer')
            ->count();
        return empty($total) ? '100%' : (round($count * 100 / $total, 2) . '%');
    }

    public static function getNextCount(string $url): int {
        if (empty($url)) {
            return 0;
        }
        return LogModel::where('referrer', $url)->count();
    }

    public static function getStayTime(string $url) {
        if (empty($url)) {
            return 0;
        }
        return StayTimeLogModel::query()->where('url', $url)
            ->where('leave_at', '>', 'enter_at')
            ->avg('leave_at - enter_at');
    }

    public static function mapGroups(int $start_at, int $end_at,
                                     callable $cb, $primary, $query = null, $key = 'referrer_hostname') {
        $items = [];
        if (empty($query) && str_starts_with($key, 'referrer')) {
            $query = LogModel::query()
                ->where('referrer_hostname', '!=', '')
                ->where('referrer_hostname', '!=', request()->host());
        }
        if (empty($query)) {
            $query = LogModel::query();
        }
        $query->where('created_at', '>=', $start_at)
            ->where('created_at', '<', $end_at)
            ->each(function ($item) use (&$items, $cb, $key) {
                $host = call_user_func($cb, $item[$key]);
                if (is_null($host) || $host === false) {
                    return;
                }
                if (!isset($items[$host])) {
                    $items[$host] = [
                        1,
                        [
                            $item['ip'],
                        ],
                        [
                            $item['session_id']
                        ]
                    ];
                    return;
                }
                $items[$host][0]++;
                if (!in_array($item['ip'], $items[$host][1])) {
                    $items[$host][1][] = $item['ip'];
                }
                if (!in_array($item['session_id'], $items[$host][2])) {
                    $items[$host][2][] = $item['session_id'];
                }
            }, $key, 'ip', 'session_id');
        $data = [];
        $pv_total = array_sum(array_column($items, 0));
        foreach ($items as $host => $item) {
            $data[] = [
                $primary => $host,
                'ip_count' => count($item[1]),
                'pv' => $item[0],
                'uv' => count($item[2]),
                'scale' => round($item[0] * 100 / $pv_total, 2) . '%'
            ];
        }
        usort($data, function ($a, $b) {
            if ($a['pv'] > $b['pv']) {
                return -1;
            }
            return $a['pv'] === $b['pv'] ? 0 : 1;
        });
        return $data;
    }

    public static function jump(int $start_at, int $end_at) {
        $items = JumpLogModel::query()
            ->where('created_at', '>=', $start_at)
            ->where('created_at', '<', $end_at)
            ->orderBy('created_at', SORT_DESC)
            ->page();
        $items->map(function ($item) {
            return static::formatAgent($item);
        });
        return $items;
    }

    public static function formatAgent(mixed $item): mixed {
        if (!isset($item['user_agent'])) {
            return $item;
        }
        $agent = $item['user_agent'];
        if (empty($agent)) {
            return $item;
        }
        $item['device'] = UserAgent::device($agent);
        $item['os'] = UserAgent::os($agent);
        $item['browser'] = UserAgent::browser($agent);
        return $item;
    }

        /**
     * 获取每天的状态
     * @param SqlBuilder|null $where
     * @return array
     */
    public static function getDayStatus(SqlBuilder|null $where = null) {
        return self::getStatus($where, 'DAYOFMONTH', 31);
    }

    /**
     * 获取每小时的状态
     * @param SqlBuilder|null $where
     * @return array
     */
    public static function getHourStatus(SqlBuilder|null $where = null) {
        return self::getStatus($where, 'HOUR', 24);
    }

    public static function getWeekStatus(SqlBuilder|null $where = null) {
        return self::getStatus($where, 'DAYOFWEEK', 7);
    }

    public static function getMonthStatus(SqlBuilder|null $where = null) {
        return self::getStatus($where, 'MONTH', 12);
    }

    public static function getYearStatus(SqlBuilder|null $where = null) {
        return self::getStatus($where, 'YEAR');
    }

    /**
     * 获取IP最后访问时间及来路
     * @return Query
     */
    public static function getLastQuery() {
        return LogModel::query()->selectRaw('ip, MAX(created_at) as created_at, referrer')
            ->groupBy(1)->orderBy('2', SORT_DESC);
    }

    /**
     * 获取所有的月份
     * @param string|array $where
     * @return array
     */
    public static function geAllMonth(SqlBuilder|null $where = null) {
        return LogModel::query()
            ->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAYOFMONTH(created_at) as day, COUNT(*) as count,COUNT(DISTINCT ip) as countIp')
            ->groupBy('1,2,3')->orderBy('1,2,3')->take(30)->get();
    }

    /**
     * 获取前三十名访问的网址
     * @param array|string $where
     * @return mixed
     */
    public static function geTopUrl(SqlBuilder|null $where = null) {
        return LogModel::query()->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->selectRaw('url, COUNT(*) as count')
            ->groupBy('1')->orderBy('2', SORT_DESC)->take(30)->get();
    }

    /**
     * 获取前三十名访问者的IP
     * @param array|string $where
     * @return mixed
     * @throws \Exception
     */
    public static function geTopIp(SqlBuilder|null $where = null) {
        return LogModel::query()->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->selectRaw('ip, COUNT(*) as count')
            ->groupBy('1')->orderBy('2', SORT_DESC)->take(30)->get();
    }

    /**
     * 获取前三十名访问者浏览器
     * @param array|string $where
     * @return mixed
     * @throws \Exception
     */
    public static function geTopBrowser(SqlBuilder|null $where = null) {
        return LogModel::query()->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->selectRaw('user_agent as browser, COUNT(*) as count')
            ->groupBy('1')->orderBy('2', SORT_DESC)->take(30)->get();
    }

    /**
     * 获取前三十名访问者系统
     * @param array|string $where
     * @return mixed
     * @throws \Exception
     */
    public static function geTopOs(SqlBuilder|null $where = null) {
        return LogModel::query()->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->selectRaw('user_agent as os, COUNT(*) as count')
            ->groupBy('1')->orderBy('2', SORT_DESC)->take(30)->get();
    }

    /**
     * 获取前三十名访问者国家
     * @param array|string $where
     * @return mixed
     * @throws \Exception
     */
    public static function geTopCountry(SqlBuilder|null $where = null) {
        return LogModel::query()->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->selectRaw('RIGHT(ip,INSTR(REVERSE(ip), ".")-1) as country, COUNT(*) as count')
            ->groupBy('1')->orderBy('2', SORT_DESC)->take(30)->get();
    }

    /**
     * 获取前三十名搜索引擎来源
     * @param array|string $where
     * @return mixed
     */
    public static function getTopSearch(SqlBuilder|null $where = null) {
        $data = LogModel::selectRaw('referrer_pathname,COUNT(*) as count')
            ->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })
            ->whereRaw("(INSTR(referrer_pathname,'?q=') OR INSTR(referrer_pathname, '?wd=') OR INSTR(referrer_pathname,'?p=') OR INSTR(referrer_pathname,'?query=') OR INSTR(referrer_pathname,'?qkw=') OR INSTR(referrer_pathname,'?search=') OR INSTR(referrer_pathname,'?qr=') OR INSTR(referrer_pathname,'?string='))")
            ->groupBy('1')->orderBy('2', SORT_DESC)->take(30)->get();
        $args = [];
        $urls = [];
        foreach ($data as $item) {
            if(preg_match('#//([\w\.]+?)/.*?\?[a-z]+=([^&]+)#i', $item['referrer_pathname'], $match)) {
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
    public static function getTopReferer(SqlBuilder|null $where = null) {
        if (empty($where)) {
            $where = [];
        }
        if (!is_array($where)) {
            $where = (array)$where;
        }
        $allUrls = LogModel::query()->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->select('url')->get();
        $search = '%'.request()->host().'%';
        $args = LogModel::selectRaw('referrer_hostname,COUNT(*) as count')
            ->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })
            ->where('referrer_hostname', 'NOT LIKE', $search)
            ->groupBy('1')->orderBy('2', SORT_DESC)->take(20)->get();
        $urls = LogModel::where('url')->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->where('referrer_hostname', 'NOT LIKE', $search)->get();
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
    public static function getStatus(SqlBuilder|null $where = null, $type = 'DAYOFMONTH', $length = null) {
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
        $args = LogModel::selectRaw($type.'(created_at) as d, COUNT(*) as c')
            ->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->groupBy('1')->orderBy('1', SORT_ASC)->get();
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

        $uvs = $ips = LogModel::selectRaw($type.'(create_at) as d, session')
            ->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->groupBy('1,2')->orderBy('1', SORT_ASC)->get();
        foreach ($uvs as $item) {
            if (!array_key_exists($item['d'], $flowCount)) {
                continue;
            }
            $flowCount[$item['d']][1] ++;
        }

        $ips = LogModel::selectRaw($type.'(created_at) as d, ip')
            ->when(!empty($where), function($query) use ($where) {
                $query->whereRaw($where);
            })->groupBy('1,2')->orderBy('1', SORT_ASC)->get();
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
