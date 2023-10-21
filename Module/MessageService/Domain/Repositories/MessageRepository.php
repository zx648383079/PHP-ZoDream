<?php
declare(strict_types=1);
namespace Module\MessageService\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\MessageService\Domain\Entities\LogEntity;
use Module\MessageService\Domain\Entities\TemplateEntity;
use Module\SEO\Domain\Model\OptionModel;
use Zodream\Helpers\Json;
use Zodream\Html\Input;
use Zodream\Html\InputHelper;

final class MessageRepository {

    public static function optionInput(bool $isMail = true): array {
        if ($isMail) {
            return [
                Input::text('host', '服务器'),
                Input::number('port', '端口'),
                Input::text('name', '发送者'),
                Input::text('user', '账号', false),
                Input::password('password', '密码', false),
            ];
        }
        return [
            Input::select('protocol', '接口', [
                ['name' => 'IHuYi', 'value' => 'ihuiyi'],
                ['name' => '阿里云短信', 'value' => 'aliyun'],
                ['name' => '阿里大于', 'value' => 'alidayu'],
            ]),
            Input::text('app_key', 'APP KEY'),
            Input::text('secret', 'SECRET', false),
            Input::text('sign_name', '签名', false),
        ];
    }

    public static function optionForm(bool $isMail = true): array {
        return InputHelper::patch(static::optionInput($isMail), static::option($isMail));
    }

    public static function option(bool $isMail = true): array {
        if ($isMail) {
            return OptionModel::findCodeJson(MessageProtocol::OPTION_MAIL_KEY, [
                'host'     => 'smtp.qq.com',
                'port'     => 25,
                'user'     => '',
                'name'     => 'ZoDream',
                'password' => ''
            ]);
        }
        return OptionModel::findCodeJson(MessageProtocol::OPTION_SMS_KEY, [
            'protocol'     => '',
            'app_key'     => '',
            'secret'     => '',
            'sign_name' => ''
        ]);
    }

    public static function optionSave(array|object $data, bool $isMail = true): void {
        $data = InputHelper::value(static::optionInput($isMail), $data);
        OptionModel::insertOrUpdate($isMail ? MessageProtocol::OPTION_MAIL_KEY : MessageProtocol::OPTION_SMS_KEY,
            Json::encode(
            $data
        ), $isMail ? 'Mail Smtp 配置' : 'SMS配置');
    }

    public static function templateList(string $keywords = '', int $type = -1) {
        return TemplateEntity::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name', 'title']);
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type);
            })->orderBy('id', 'desc')->page();
    }

    public static function template(int $id): mixed {
        return TemplateEntity::findOrThrow($id);
    }

    public static function templateSave(array $data): mixed {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = TemplateEntity::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function templateRemove(array|int $id): void {
        $items = ModelHelper::parseArrInt($id);
        if (empty($items)) {
            return;
        }
        TemplateEntity::whereIn('id', $items)->delete();
    }

    public static function logList(string $keywords = '', int $status = -1) {
        return LogEntity::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['target', 'template_name']);
            })->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            })->orderBy('id', 'desc')->page();
    }

    public static function logRemove(array|int $id): void {
        $items = ModelHelper::parseArrInt($id);
        if (empty($items)) {
            return;
        }
        LogEntity::whereIn('id', $items)->delete();
    }

    public static function logClear() {
        LogEntity::query()->delete();
    }
}