<?php
declare(strict_types=1);
namespace Module\SMS\Domain;

use Module\SMS\Domain\Model\SmsLogModel;
use Zodream\Helpers\Str;

class Sms {

    const KEY = 'sms_log';
    const TIME_KEY = 'time';
    const MOBILE_KEY = 'mobile';
    const CODE_KEY = 'code';

    private array $configs = [
        'space' => 120,
        'everyone' => 20,
        'everyday' => 2000
    ];

    public function send(string $mobile, string $content): bool {
        $log = SmsLogModel::createOrThrow([
            'mobile' => $mobile,
            'content' => $content,
            'ip' => request()->ip(),
            'status' => 0,
            'created_at' => time()
        ]);
        // TODO 调用短信接口
        $log->status = 1;
        $log->save();
        return true;
    }

    public function sendCode(string $mobile): bool {
        $code = Str::randomNumber(6);
        if (!$this->send($mobile, $code)) {
            return false;
        }
        session()->set(self::KEY, [
            self::TIME_KEY => time(),
            self::MOBILE_KEY => $mobile,
            self::CODE_KEY => $code
        ]);
        return true;
    }

    public function verifyCode(string $mobile, string $code): bool {
        $log = session(self::KEY);
        return !empty($log) && isset($log[self::CODE_KEY])
            && $log[self::MOBILE_KEY] == $mobile
            && $log[self::CODE_KEY] == $code;
    }

    public function verifySpace(): bool {
        $log = session(self::KEY);
        if (empty($log)) {
            return true;
        }
        return time() - $log[self::TIME_KEY] > $this->configs['space'];
    }

    public function verifyIp(): bool {
        $time = strtotime(date('Y-m-d'));
        $count = SmsLogModel::where('ip', request()->ip())->where('created_at', '>=', $time)->count();
        return $count < $this->configs['everyone'];
    }

    public function verifyCount(): bool {
        $time = strtotime(date('Y-m-d'));
        $count = SmsLogModel::where('created_at', '>=', $time)->count();
        return $count < $this->configs['everyday'];
    }
}