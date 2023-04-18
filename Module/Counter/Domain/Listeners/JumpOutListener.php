<?php
namespace Module\Counter\Domain\Listeners;


use Module\Counter\Domain\Events\JumpOut;
use Module\Counter\Domain\Model\JumpLogModel;
use Zodream\Helpers\Str;

class JumpOutListener {

    public function __construct(JumpOut $jump) {
        $model = new JumpLogModel();
        $model->ip = $jump->getIp();
        $model->url = Str::substr($jump->getUrl(), 0, 255);
        $model->referrer = Str::substr($jump->getReferrer(), 0, 255);
        $model->session_id = $jump->getSessionId();
        $model->user_agent = Str::substr($jump->getUserAgent(), 0, 255);
        $model->created_at = $jump->getTimestamp();
        $model->save();
    }
}
