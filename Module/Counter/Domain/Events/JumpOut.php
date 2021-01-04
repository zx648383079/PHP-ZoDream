<?php
namespace Module\Counter\Domain\Events;

class JumpOut {
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

    public function __construct(
        string $ip,
        string $referrer,
        string $url,
        string $userAgent,
        int $timestamp,
        string $sessionId
    ) {
        $this->ip = $ip;
        $this->referrer = (string)$referrer;
        $this->url = (string)$url;
        $this->userAgent = $userAgent;
        $this->timestamp = $timestamp;
        $this->sessionId = $sessionId;
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


    public static function create(string $url) {
        $request = request();
        return new static($request->ip(),
            $request->referrer(),
            $url,
            $request->server('HTTP_USER_AGENT', '-'),
            time(),
            session()->id()
        );
    }
}