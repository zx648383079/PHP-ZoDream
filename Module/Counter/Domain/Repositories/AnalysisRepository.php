<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Counter\Domain\Importers\ApacheImporter;
use Module\Counter\Domain\Importers\IISImporter;
use Module\Counter\Domain\Importers\NginxImporter;
use Module\Counter\Domain\Model\AnalysisFlagModel;
use Module\Counter\Domain\Model\LogModel;
use Zodream\Disk\File;
use Zodream\Helpers\Str;
use Zodream\Html\Page;
use Zodream\Infrastructure\Support\UserAgent;

final class AnalysisRepository
{


    public static function logList(string $keywords = '', string $start_at = '', string $end_at = '',
                                   string $ip = '', string $goto = '', int $page = 1,
                                   int $per_page = 20) : Page
    {
        if (!empty($goto)) {
            $count = LogModel::query()->when(!empty($start_at), function ($query) use ($start_at) {
                $query->where('created_at', '>=', strtotime($start_at));
            })->when(!empty($end_at), function ($query) use ($end_at) {
                $query->where('created_at', '<=', strtotime($end_at));
            })->when(!empty($ip), function ($query) use ($ip) {
                $query->where('ip', $ip);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['pathname', 'queries'], false);
            })
            ->where('created_at', '>=', strtotime($goto))
            ->count();
            $page = (int)ceil((float)$count / $per_page);
        }
        $res = LogModel::query()->when(!empty($start_at), function ($query) use ($start_at) {
            $query->where('created_at', '>=', strtotime($start_at));
        })->when(!empty($end_at), function ($query) use ($end_at) {
            $query->where('created_at', '<=', strtotime($end_at));
        })->when(!empty($ip), function ($query) use ($ip) {
            $query->where('ip', $ip);
        })->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['pathname', 'queries'], false);
        })->orderBy('created_at', 'desc')
            ->page($per_page, 'page', $page);
        $res->map(function (LogModel $item) {
            $data = $item->toArray();
            $data['os'] = UserAgent::os($data['user_agent']);
            $data['browser'] = UserAgent::browser($data['user_agent']);
            $data['device'] = UserAgent::device($data['user_agent']);
            return $data;
        });
        return $res;
    }

    public static function logImport(string $hostname, string $engine, string $fields, File $file): void
    {
        $importer = match (strtolower($engine))
        {
            'iis' => new IISImporter(),
            'apache' => new ApacheImporter(),
            default => new NginxImporter()
        };
        $headers = $importer->parseFields($fields);
        $last = intval(LogModel::query()->where('hostname', $hostname)
            ->max('created_at'));
        $batchItems = [];
        $importer->read($headers, $file, function(array $log) use ($hostname, $last, &$batchItems) {
            if ($log['created_at'] <= $last)
            {
                return false;
            }
            if (empty($log['hostname']))
            {
                $log['hostname'] = $hostname;
            }
            if (isset($log['pathname']) && mb_strlen($log['pathname']) > 255) {
                $log['queries'] = sprintf('%s?%s', mb_substr($log['pathname'], 255), $log['queries']);
                $log['pathname'] = Str::substr($log['pathname'], 0, 255);
            }
            if (isset($log['queries'])) {
                $log['queries'] = Str::substr($log['queries'], 0, 1000);
            }
            if (isset($log['user_agent'])) {
                $log['user_agent'] = Str::substr($log['user_agent'], 0, 1000);
            }
            if (isset($log['referrer_pathname'])) {
                $log['referrer_pathname'] = Str::substr($log['referrer_pathname'], 0, 255);
            }
            $batchItems[] = $log;
            if (count($batchItems) >= 500) {
                LogModel::query()->insert($batchItems);
                $batchItems = [];
            }
            return true;
        });
        if (!empty($batchItems)) {
            LogModel::query()->insert($batchItems);
            $batchItems = [];
        }
    }

    public static function mask(array $data) : AnalysisFlagModel
    {
        $userId = auth()->id();
        $itemType = intval($data['type']);
        $itemValue = $data['value'];
        $log = AnalysisFlagModel::query()->where('user_id', $userId)
            ->where('item_type', $itemType)
            ->where('item_value', $itemValue)
            ->first();
        if (!empty($log)) {
            return $log;
        }
        $log = new AnalysisFlagModel();
        $log->user_id = $userId;
        $log->item_type = $itemType;
        $log->item_value = $itemValue;
        $log->save();
        return $log;
    }
}