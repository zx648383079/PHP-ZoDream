<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Repositories;

use Module\Counter\Domain\Model\JumpLogModel;
use Module\Counter\Domain\Model\LogModel;
use Module\Counter\Domain\Model\StayTimeLogModel;
use Zodream\Html\Page;
use Zodream\Infrastructure\Support\UserAgent;

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
        $items = StayTimeLogModel::query()->orderBy('id', 'desc')->page();
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
            ->orderBy('created_at', 'desc')
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
}
