<?php
namespace Module\Shop\Domain\Events;

use Module\Shop\Domain\Models\PayLogModel;

/**
 * 支付记录支付成功事件
 * @package Module\Shop\Domain\Events
 */
class PaySuccess {
    /**
     * @var PayLogModel
     */
    private $log;

    public function __construct(PayLogModel $log) {
        $this->log = $log;
    }

    /**
     * @return PayLogModel
     */
    public function getLog(): PayLogModel {
        return $this->log;
    }

    public function getType(): int {
        return intval($this->log->type);
    }
}