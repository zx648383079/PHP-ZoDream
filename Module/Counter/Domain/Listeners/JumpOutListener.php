<?php
namespace Module\Counter\Domain\Listeners;


use Module\Counter\Domain\Events\JumpOut;
use Module\Counter\Domain\Model\JumpLogModel;

class JumpOutListener {

    public function __construct(JumpOut $jump) {
        $model = new JumpLogModel();
        $model->ip = $jump->getIp();
        $model->url = $jump->getUrl();
        $model->referrer = $jump->getReferrer();
        $model->session_id = $jump->getSessionId();
        $model->user_agent = $jump->getUserAgent();
        $model->created_at = $jump->getTimestamp();
        $model->save();
    }
}
