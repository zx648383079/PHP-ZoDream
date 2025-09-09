<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Listeners;

use Module\Counter\Domain\Events\Visit;
use Module\Counter\Domain\Importers\NginxImporter;
use Module\Counter\Domain\Model\LogModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Support\UserAgent;

final class VisitListener {

    public function __construct(Visit $visit) {
        $model = new LogModel();
//        $os = UserAgent::os($visit->getUserAgent());
//        $browser = UserAgent::browser($visit->getUserAgent());
        $model->ip = $visit->getIp();
//        $model->browser = $browser[0];
//        $model->browser_version = $browser[1];
//        $model->os = $os[0];
//        $model->os_version = $os[1];
        $args = parse_url($visit->getReferrer());
        $model->referrer_hostname = Str::substr($args['host'] ?? '', 0, 255);
        $model->referrer_pathname = Str::substr(NginxImporter::combinePathQueries($args['path'] ?? '',
            $args['query'] ?? ''), 0, 1000);
        $args = parse_url($visit->getUrl());
        $model->hostname = Str::substr($args['host'] ?? '', 0, 255);
        $model->pathname = Str::substr($args['path'] ?? '', 0, 255);
        $model->queries = Str::substr($args['query'] ?? '', 0, 1000);
        $model->session_id = $visit->getSessionId();
        $model->user_agent = Str::substr($visit->getUserAgent(), 0, 255);
        $model->user_id = $visit->getUserId();
        $model->created_at = $visit->getTimestamp();
        $model->save();
    }
}
