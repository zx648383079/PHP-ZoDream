<?php
declare(strict_types = 1);
namespace Module\Counter\Domain\Listeners;

use Module\Counter\Domain\Events\CounterState;
use Module\Counter\Domain\Model\HostnameModel;
use Module\Counter\Domain\Model\LoadTimeLogModel;
use Module\Counter\Domain\Model\LogModel;
use Module\Counter\Domain\Model\PageLogModel;
use Module\Counter\Domain\Model\PathnameModel;
use Module\Counter\Domain\Model\StayTimeLogModel;
use Module\Counter\Domain\Model\VisitorLogModel;

final class StateListener
{
    public function __construct(CounterState $state) {
        // ClickLogModel::log($state);
        $uri = parse_url($state->url);
        if ($state->status === CounterState::STATUS_LOADED) {
            LoadTimeLogModel::create([
                'url' => $state->url,
                'ip' => $state->ip,
                'session_id' => $state->session_id,
                'user_agent' => $state->session_id,
                'load_time' => $state->getLoadTime(),
            ]);
        }
        if ($state->status === CounterState::STATUS_ENTER) {
            $hostId = self::getHostId($uri['host'] ?? '');
            $pathId = self::getPathId($uri['path'] ?? '');
            $model = PageLogModel::query()->where('host_id', $hostId)
                ->where('path_id', $pathId)->first();
            if ($model) {
                $model->visit_count ++;
                $model->save();
            } else {
                PageLogModel::create([
                    'host_id' => $hostId,
                    'path_id' => $pathId,
                    'visit_count' => 1
                ]);
            }
        }
        if ($state->status === CounterState::STATUS_ENTER) {
            $ip = $state->ip;
            $user_id = $state->user_id;
            $model = VisitorLogModel::where('ip', $ip)->where('user_id', $user_id)->first();
            if ($model) {
                $model->last_at = $state->getTimeOrNow('leave_at');
                $model->save();
            } else {
                VisitorLogModel::create([
                    'ip' => $ip,
                    'user_id' => $user_id,
                    'first_at' => $state->getTimeOrNow('enter_at'),
                    'last_at' => $state->getTimeOrNow('leave_at'),
                ]);
            }
        }
        $logId = LogModel::query()->where('ip', $state->ip)
                ->where('hostname', $uri['host']??'')
                ->where('pathname', $uri['path']??'')
                ->where('queries', $uri['query']??'')
                ->where('user_agent', $state->user_agent)
                ->where('session_id', $state->session_id)
                ->where('created_at', '<=', $state->getTimeOrNow('enter_at'))
                ->orderBy('created_at', 'desc')
                ->value('id');
        if ($logId < 1) {
            return;
        }
        if ($state->status === CounterState::STATUS_ENTER) {
            StayTimeLogModel::create([
                'log_id' => $logId,
                'status' => $state->status,
                'enter_at' => $state->getTimeOrNow('enter_at'),
                'leave_at' => 0,
            ]);
        }
        if ($state->status === CounterState::STATUE_LEAVE) {
            StayTimeLogModel::where('log_id', $logId)
                ->where('leave_at', 0)->orderBy('id', 'desc')
                ->limit(1)
                ->update([
                    'status' => $state->status,
                    'leave_at' => $state->getTimeOrNow('leave_at')
                ]);
        }
    }

    private static function getHostId(string $hostname): int
    {
        if (empty($hostname)) {
            return 0;
        }
        $id = HostnameModel::query()->where('name', $hostname)->value('id');
        if ($id > 0) {
            return intval($id);
        }
        return (int)HostnameModel::query()->insert([
            'name' => $hostname,
        ]);
    }

    private static function getPathId(string $pathname): int
    {
        if (empty($pathname)) {
            return 0;
        }
        $id = PathnameModel::query()->where('name', $pathname)->value('id');
        if ($id > 0) {
            return intval($id);
        }
        return (int)PathnameModel::query()->insert([
            'name' => $pathname,
        ]);
    }
}