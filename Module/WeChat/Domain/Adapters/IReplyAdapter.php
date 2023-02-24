<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Adapters;

use Module\Auth\Domain\Model\UserModel;
use Zodream\Infrastructure\Contracts\Http\Output;

interface IReplyAdapter {

    public function platformId(): int;

    public function oAuthType(): string;

    public function listen(): Output;

    /**
     * @return array{from:string,event:AdapterEvent,content: string,created_at:int}
     */
    public function receive(): array;

    /**
     * @param Output $output
     * @param array{to:string,type:int,content:string} $data
     * @return void
     */
    public function reply(Output $output, array $data);

    public function authUser(string $openId): ?UserModel;
    public function authUserId(string $openId): int;
}