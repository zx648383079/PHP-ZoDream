<?php
declare(strict_types=1);
namespace Module\MessageService\Domain\Repositories;

use Module\MessageService\Domain\Entities\LogEntity;
use Module\MessageService\Domain\Entities\TemplateEntity;
use Module\OpenPlatform\Domain\Platform;
use Module\SEO\Domain\Option;
use Zodream\Database\Model\Model;
use Zodream\Helpers\Html;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Mailer\Mailer;
use Zodream\ThirdParty\SMS\ALiDaYu;
use Zodream\ThirdParty\SMS\ALiYun;
use Zodream\ThirdParty\SMS\IHuYi;
use Zodream\ThirdParty\SMS\IShortMessageProtocol;
use Zodream\Validate\Validator;

class MessageProtocol {

    const SESSION_KEY = 'ms_code';

    const OPTION_MAIL_KEY = 'mail_protocol';
    const OPTION_SMS_KEY = 'sms_protocol';
    const TYPE_TEXT = 1;
    const TYPE_HTML = 5;

    const RECEIVE_TYPE_MOBILE = 1;
    const RECEIVE_TYPE_EMAIL = 2;

    const STATUS_NONE = 0;
    const STATUS_SENDING = 1;

    const STATUS_SEND_FAILURE = 4;

    const STATUS_SENT = 6;
    const STATUS_SENT_USED = 7;
    const STATUS_SENT_EXPIRED = 9;

    const EVENT_LOGIN_CODE = 'login_code';
    const EVENT_REGISTER_CODE = 'register_code';
    const EVENT_FIND_CODE = 'find_code';

    private static array $configs = [
        'space' => 120,    // 发送间隔
        'everyone' => 20,  // 每个ip一天数量
        'everyday' => 2000 // 每天发送数量
    ];

    public static function sendCode(string $target, string $templateName,
                                    string $code, array $extra = []): bool {
        if (!static::verifySpace($target) || !static::verifyIp() || !static::verifyCount()) {
            throw new \Exception('发送过于频繁');
        }
        $res = static::send($target, $templateName, array_merge($extra, compact('code')));
        if ($res) {
            LogEntity::where('target', $target)->where('template_name', $templateName)
                ->where('status', static::STATUS_SENT)
                ->update([
                    'status' => static::STATUS_SENT_EXPIRED
                ]);
        }
        if ($res && !Platform::isPlatform()) {
            session()->set(self::SESSION_KEY, [
                'at' => time(),
                'to' => $target,
                'code' => $code
            ]);
        }
        return $res;
    }

    public static function send(string $target, string $templateName, array $data): bool {
        list($type, $option, $optionKey) = static::targetOption($target);
        $template = TemplateEntity::where('name', $templateName)
            ->when($type === static::RECEIVE_TYPE_MOBILE, function ($query) {
                $query->where('type', static::TYPE_TEXT);
            }, function ($query) {
                $query->orderBy('type', 'desc');
            })
            ->first();
        if (empty($template)) {
            throw new \Exception(sprintf('未配置相关模板[%s:%s]', $optionKey, $templateName));
        }
        // TODO 根据模板值过滤
        $data = static::filterData($data, $template['data']);
        $log = LogEntity::createOrThrow([
            'template_id' => $template['id'],
            'target_type' => $type,
            'target' => $target,
            'template_name' => $templateName,
            'type' => $template['type'],
            'title' => $template['title'],
            'content' => static::renderTemplate($template['content'], $data),
            'status' => static::STATUS_SENDING,
            'ip' => request()->ip(),
        ]);
        $res = false;
        try {
            $res = $type === static::RECEIVE_TYPE_MOBILE ? static::sendSMS($option, $target, $template, $data) : static::sendMail($option, $target, $template, $data);
            $log->status = $res === false ? static::STATUS_SENT : static::STATUS_SEND_FAILURE;
            $log->message = is_string($res) ? $res : '';
        } catch (\Exception $ex) {
            $log->status = static::STATUS_SEND_FAILURE;
            $log->message = $ex->getMessage();
        }
        $log->save();
        return $res;
    }

    /**
     * 根据目标获取配置信息
     * @param string $target
     * @return array
     * @throws \Exception
     */
    protected static function targetOption(string $target): array {
        if (empty($target)) {
            throw new \Exception('无效接受者');
        }
        if (Validator::email()->validate($target)) {
            $type = static::RECEIVE_TYPE_EMAIL;
        } else if (Validator::phone()->validate($target)) {
            $type = static::RECEIVE_TYPE_MOBILE;
        } else {
            throw new \Exception('无效接受者');
        }
        $optionKey = $type === static::RECEIVE_TYPE_MOBILE ? static::OPTION_SMS_KEY : static::OPTION_MAIL_KEY;
        $option = Json::decode(Option::value($optionKey));
        if (empty($option)) {
            throw new \Exception(sprintf('未配置相关参数[%s]', $optionKey));
        }
        return [$type, $option, $optionKey];
    }

    protected static function filterData(array $data, mixed $filterKeys): array {
        if (!is_array($filterKeys)) {
            $filterKeys = Json::decode($filterKeys);
        }
        if (empty($filterKeys)) {
            return $data;
        }
        $items = [];
        foreach ($data as $key => $val) {
            if (in_array($key, $filterKeys) || isset($key, $filterKeys)) {
                $items[$key] = $val;
            }
        }
        return $items;
    }

    public static function verifyCode(string $target, string $templateName, string $code, bool $once = true): bool {
        if (!Platform::isPlatform()) {
            $log = session(self::SESSION_KEY);
            if (empty($log) || empty($log['code'])
                || $log['to'] !== $target
                || $log['code'] !== $code) {
                return false;
            }
        }
        $log = LogEntity::where('target', $target)->where('template_name', $templateName)
            ->where('status', static::STATUS_SENT)
            ->first();
        if (empty($log)) {
            return false;
        }
        $data = Json::decode($log['data']);
        $res = empty($data['code']) && $data['code'] === $code;
        if ($once) {
            $log->status = self::STATUS_SENT_USED;
            $log->save();
        }
        if ($once && !Platform::isPlatform()) {
            session()->delete(self::SESSION_KEY);
        }
        return $res;
    }


    protected static function sendMail(array $option, string $target,
                                       array|Model $template, array $data): bool|string {
        return static::sendMailOrThrow($option, $target,
            $data['name'] ?? $target, $template['title'],
            static::renderTemplate($template['content'], $data),
            $template['type'] > MessageProtocol::TYPE_TEXT);
    }

    protected static function sendMailOrThrow(array $option, string $target, string $targetName,
                                              string $title, string $content, bool $isHtml = true): bool {
        $mail = new Mailer($option);
        $res = $mail->isHtml($isHtml)
            ->addAddress($target, $targetName)
            ->send($title, $content);
        if (!$res) {
            throw new \Exception($mail->getError() ?? 'smtp send error');
        }
        return true;
    }

    protected static function renderTemplate(string $content, array $data): string {
        return trans()->format($content, $data);
    }

    /**
     * 自定义方式内容
     * @param string $target
     * @param string $title
     * @param string|callable $content
     * @param bool $isHtml
     * @return void
     */
    public static function sendCustom(string $target,
                                      string $title,
                                      string|callable $content, bool $isHtml = true): void {
        list($type, $option) = static::targetOption($target);
        if (is_callable($content)) {
            $content = call_user_func($content);
        }
        if (empty($content)) {
            throw new \Exception('发送内容不能为空');
        }
        $log = LogEntity::createOrThrow([
            'target_type' => $type,
            'target' => $target,
            'type' => $isHtml ? MessageProtocol::TYPE_HTML : MessageProtocol::TYPE_TEXT,
            'title' => $title,
            'content' => $content,
            'status' => static::STATUS_SENDING,
            'ip' => request()->ip(),
        ]);
        try {
            if ($type === static::RECEIVE_TYPE_MOBILE) {
                if ($isHtml) {
                    $content = Html::toText($content);
                }
                $api = static::SMSProtocol($option);
                if (!$api->isOnlyTemplate()) {
                    throw new \Exception('不支持自定义内容');
                }
                $res = $api->send($target, $content, [], $option['sign_name'] ?? '');
            } else {
                $res = static::sendMailOrThrow($option, $target, $target, $title, $content, $isHtml);
            }
            $log->status = $res === false ? static::STATUS_SENT : static::STATUS_SEND_FAILURE;
            $log->message = is_string($res) ? $res : '';
        } catch (\Exception $ex) {
            $log->status = static::STATUS_SEND_FAILURE;
            $log->message = $ex->getMessage();
        }
        $log->save();
    }

    protected static function renderTemplateFile(string $fileName, array $data): string {
        return view()->render('@root/Template/'.$fileName, $data);
    }


    protected static function sendSMS(array $option, string $target,
                                      array|Model $template, array $data): bool|string {
        $api = static::SMSProtocol($option);
        if ($api->isOnlyTemplate()) {
            return $api->send($target, $template['target_no'], $data, $option['sign_name'] ?? '');
        }
        return $api->send($target, static::renderTemplate($template['content'], $data),
            $data, $option['sign_name'] ?? '');
    }

    /**
     * @param array $option {protocol: string, app_key: string, secret: string}
     * @return IShortMessageProtocol
     */
    protected static function SMSProtocol(array $option): IShortMessageProtocol {
        return match ($option['protocol'] ?? '') {
            'alidayu' => new ALiDaYu($option),
            'aliyun' => new ALiYun(array_merge($option, ['AccessKeyId' => $option['app_key']])),
            default => new IHuYi(array_merge($option, ['account' => $option['app_key'],
                'password' => $option['secret']]))
        };
    }

    protected static function verifyCount(): bool {
        if (self::$configs['everyday'] < 1) {
            return true;
        }
        $time = strtotime(date('Y-m-d'));
        $count = LogEntity::where('created_at', '>=', $time)->count();
        return $count < self::$configs['everyday'];
    }

    protected static function verifySpace(string $target): bool {
        if (!Platform::isPlatform()) {
            $log = session(self::SESSION_KEY);
            return empty($log) || (time() - $log['at']) > self::$configs['space'];
        }
        $last = LogEntity::where('target', $target)->where('status', '!=', static::STATUS_SEND_FAILURE)
            ->max('created_at');
        if (empty($last)) {
            return true;
        }
        return time() - $last > self::$configs['space'];
    }

    protected static function verifyIp(): bool {
        if (self::$configs['everyone'] < 1) {
            return true;
        }
        $time = strtotime(date('Y-m-d'));
        $count = LogEntity::where('ip', request()->ip())->where('created_at', '>=', $time)->count();
        return $count < self::$configs['everyone'];
    }

}