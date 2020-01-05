<?php
namespace Module\Counter\Domain\Repositories;

use Module\Counter\Domain\Model\JumpLogModel;
use Module\Counter\Domain\Model\LogModel;
use Module\Counter\Domain\Model\StayTimeLogModel;
use Zodream\Helpers\Str;
use Zodream\Html\Page;

class StateRepository {

    public static function statisticsByTime(int $start_at, int $end_at): array {
        $pv = StayTimeLogModel::where('enter_at',  '>=', $start_at)
            ->where('enter_at',  '<', $end_at)->count();
        $uv = StayTimeLogModel::where('enter_at',  '>=', $start_at)
            ->where('enter_at',  '<', $end_at)
            ->groupBy('session_id')->count();
        $ip = StayTimeLogModel::where('enter_at',  '>=', $start_at)
            ->where('enter_at',  '<', $end_at)
            ->groupBy('ip')->count();
        $jump = JumpLogModel::where('created_at',  '>=', $start_at)
            ->where('created_at',  '<', $end_at)->count();
        $stay = (int)StayTimeLogModel::query()->where('enter_at',  '>=', $start_at)
            ->where('enter_at',  '<', $end_at)
            ->where('leave_at', '>', 0)
            ->avg('leave_at - enter_at');

        return compact('pv', 'uv', 'ip', 'jump', 'stay');
    }

    public static function currentStay() {
        return StayTimeLogModel::query()->orderBy('id', 'desc')->page();
    }

    public static function allUrl(int $start_at, int $end_at) {
        $key = __CLASS__.__METHOD__.$start_at.$end_at;
        $items = cache()->getOrSet($key, function () use ($start_at, $end_at) {
            return self::mapGroups($start_at, $end_at, function ($url) {
                if (empty($url)) {
                    return false;
                }
                return $url;
            }, 'url', null, 'url');
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
                    $query->where('referrer', '==', '')
                        ->orWhere('referrer', 'not like',
                            sprintf('%%%s%%', url()->getHost()));
                }), 'url');
        }, 60);
        return new Page($items);
    }

    public static function domain(int $start_at, int $end_at) {
        return self::mapGroups($start_at, $end_at, function ($url) {
            if (empty($url)) {
                return;
            }
            $host = parse_url($url, PHP_URL_HOST);
            if (empty($host)) {
                return;
            }
            if (substr($host, 0, 4) === 'www.') {
                return substr($host, 4);
            }
            return $host;
        }, 'host', null, 'url');
    }

    private static function isSearch($url) {
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
            if (substr($host, 0, 4) === 'www.') {
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
            if (substr($host, 0, 4) === 'www.') {
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
            if (substr($host, 0, 4) === 'www.') {
                return substr($host, 4);
            }
            return $host;
        }, 'host', LogModel::query());
    }

    public static function mapGroups(int $start_at, int $end_at,
                                     callable $cb, $primary, $query = null, $key = 'referrer') {
        $items = [];
        if (empty($query) && $key === 'referrer') {
            $query = LogModel::query()
                ->where('referrer', '!=', '')
                ->where('referrer', 'not like',
                    sprintf('%%%s%%', url()->getHost()));
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
                'ip' => count($item[1]),
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
}
