<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Listeners;

use Module\Counter\Domain\Events\Visit;
use Module\Counter\Domain\Model\LogModel;
use Zodream\Infrastructure\Support\UserAgent;

class VisitListener {

    public function __construct(Visit $visit) {
        $model = new LogModel();
        $os = UserAgent::os($visit->getUserAgent());
        $browser = UserAgent::browser($visit->getUserAgent());
        $model->ip = $visit->getIp();
        $model->browser = $browser[0];
        $model->browser_version = $browser[1];
        $model->os = $os[0];
        $model->os_version = $os[1];
        $model->referrer = $visit->getReferrer();
        $model->url = $visit->getUrl();
        $model->session_id = $visit->getSessionId();
        $model->user_agent = mb_substr($visit->getUserAgent(), 0, 255);
        $model->user_id = $visit->getUserId();
        $model->created_at = $visit->getTimestamp();
        $model->save();
    }
}
