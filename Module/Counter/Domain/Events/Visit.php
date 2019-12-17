<?php
namespace Module\Counter\Domain\Events;

class Visit {
    /**
     * @var string
     */
    protected $ip;
    /**
     * @var string
     */
    protected $referrer;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $userAgent;
    /**
     * @var int
     */
    protected $timestamp;
    /**
     * @var string
     */
    protected $sessionId;
    /**
     * @var int
     */
    protected $userId;

    public function __construct(
        string $ip,
        string $referrer,
        string $url,
        string $userAgent,
        int $timestamp,
        string $sessionId,
        int $userId
    ) {
        $this->ip = $ip;
        $this->referrer = (string)$referrer;
        $this->url = (string)$url;
        $this->userAgent = $userAgent;
        $this->timestamp = $timestamp;
        $this->sessionId = $sessionId;
        $this->userId = $userId;
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
        $request = app('request');
        return new static($request->ip(),
            $request->referrer(),
            $request->uri(),
            $request->server('HTTP_USER_AGENT', '-'), time(),
            session()->id(),
            auth()->id()
        );
    }
}