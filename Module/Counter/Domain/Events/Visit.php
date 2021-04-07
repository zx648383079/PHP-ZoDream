<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Events;

class Visit {

    public function __construct(
        protected string $ip,
        protected string $referrer,
        protected string $url,
        protected string $userAgent,
        protected int $timestamp,
        protected string $sessionId,
        protected int $userId
    ) {
    }

    /**
     * @return string
     */
    public function getIp(): string {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getReferrer(): string {
        return $this->referrer;
    }

    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string {
        return $this->userAgent;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getSessionId(): string {
        return $this->sessionId;
    }

    /**
     * @return int
     */
    public function getUserId(): int {
        return $this->userId;
    }


    public static function createCurrent() {
        $request = request();
        return new static($request->ip(),
            $request->referrer(),
            $request->url(),
            $request->server('HTTP_USER_AGENT', '-'), time(),
            session()->id().'',
            auth()->id()
        );
    }
}