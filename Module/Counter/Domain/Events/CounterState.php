<?php
namespace Module\Counter\Domain\Events;

use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Concerns\Attributes;

/**
 * Class CounterState
 * @package Module\Counter\Domain\Events
 * @property string $ip
 * @property string $url
 * @property string $referrer
 * @property string $user_agent
 * @property string $session_id
 * @property int $user_id
 * @property string $latitude
 * @property string $longitude
 * @property int $enter_at
 * @property int $leave_at
 * @property int $loaded_at
 * @property string $display_size
 * @property string $language
 * @property int $status
 */
class CounterState {

    const STATUS_ENTER = 0;
    const STATUS_LOADED = 1;
    const STATUE_LEAVE = 2;

    use Attributes;

    public function getLoadTime(): int {
        if (!$this->loaded_at) {
            return 0;
        }
        return $this->loaded_at - $this->enter_at;
    }

    public function getTimeOrNow(string $key) {
        $time = $this->$key;
        return $time && $time > 0 ? $time : time();
    }

    public static function create(Request $request) {
        $state = new static();
        $state->ip = $request->ip();
        $state->session_id = session()->id();
        $state->user_id = auth()->id();
        $state->user_agent = $request->server('HTTP_USER_AGENT', '-');
        $state->url = (string)$request->referrer();
        $state->referrer = $request->get('ref');
        $state->language = $request->get('ln');
        $state->latitude = $request->get('lat');
        $state->longitude = $request->get('lon');
        $state->enter_at = $request->get('enter');
        $state->loaded_at = $request->get('loaded');
        $state->leave_at = $request->get('leave');
        foreach ([
            'enter_at',
            'loaded_at',
            'leave_at'
                 ] as $key) {
            if ($state->$key && $state->$key > 0) {
                $state->$key = floor($state->$key / 1000);
            }
        }
        $state->display_size = $request->get('ds');
        $state->status = $state->leave_at ? self::STATUE_LEAVE :
            ($state->loaded_at ? self::STATUS_LOADED : self::STATUS_ENTER);
        return $state;
    }
}
