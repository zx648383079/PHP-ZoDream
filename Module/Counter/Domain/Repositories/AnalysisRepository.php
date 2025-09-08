<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Repositories;


use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Counter\Domain\Importers\ApacheImporter;
use Module\Counter\Domain\Importers\IISImporter;
use Module\Counter\Domain\Importers\NginxImporter;
use Module\Counter\Domain\Model\AnalysisFlagModel;
use Module\Counter\Domain\Model\LogModel;
use Zodream\Disk\File;
use Zodream\Html\Page;

final class AnalysisRepository
{


    public static function logList(string $start_at = '', string $end_at = '',
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
        return LogModel::query()->when(!empty($start_at), function ($query) use ($start_at) {
            $query->where('created_at', '>=', strtotime($start_at));
        })->when(!empty($end_at), function ($query) use ($end_at) {
            $query->where('created_at', '<=', strtotime($end_at));
        })->when(!empty($ip), function ($query) use ($ip) {
            $query->where('ip', $ip);
        })->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['pathname', 'queries'], false);
        })->orderBy('created_at', 'desc')
            ->page($per_page, 'page', $page);
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
        $importer->read($headers, $file, function(LogModel $log) use ($hostname, $last) {
            if ($log->getAttributeSource('created_at') <= $last)
            {
                return;
            }
            if (empty($log->getAttributeSource('hostname')))
            {
                $log->hostname = $hostname;
            }
            $log->save();
        });
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