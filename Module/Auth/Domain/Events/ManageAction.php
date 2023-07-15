<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Events;

use Module\Auth\Domain\Model\AdminLogModel;

class ManageAction {

    protected string $ip;
    protected int $userId;
    protected int $createdAt;

    public function __construct(
        protected string $action,
        protected string $remark,
        protected int $type,
        protected int|string $id
    ) {
        $request = request();
        $this->ip = $request->ip();
        $this->userId = auth()->id();
        $this->createdAt = time();
    }

    public function toLogModel() {
        return new AdminLogModel([
            'ip' => $this->ip,
            'user_id' => $this->userId,
            'item_type' => $this->type,
            'item_id' => $this->id,
            'action' => $this->action,
            'remark' => $this->remark,
            'created_at' => $this->createdAt,
        ]);
    }
}