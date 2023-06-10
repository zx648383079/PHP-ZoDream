<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;


use Module\Shop\Domain\Entities\PluginEntity;
use Zodream\Helpers\Json;

final class PluginRepository {

    public static function getList(): array {
        return PluginEntity::query()->get('code', 'status');
    }

    public static function isInstalled(string $code): bool {
        $val = PluginEntity::where('code', $code)->value('status');
        return intval($val) > 0;
    }

    public static function toggle(string $code) {
        $log = PluginEntity::where('code', $code)->first();
        if (empty($log)) {
            return PluginEntity::createOrThrow([
                'code' => $code,
                'status' => 1,
                'setting' => '',
            ]);
        }
        $log->status = $log->status > 0 ? 0 : 1;
        $log->save();
        return [
            'code' => $log->code,
            'status' => $log->status
        ];
    }

    public static function settingSave(string $code, mixed $setting) {
        if (is_array($setting) || is_object($setting)) {
            $setting = Json::encode($setting);
        }
        $id = PluginEntity::where('code', $code)->value('id');
        if (!empty($id)) {
            PluginEntity::where('id', $id)->update([
                'setting' => $setting,
            ]);
        } else {
            PluginEntity::createOrThrow([
                'code' => $code,
                'status' => 0,
                'setting' => $setting,
            ]);
        }
        return true;
    }

    public static function settingGet(string $code, array $default = []): array {
        $setting = PluginEntity::where('code', $code)->value('setting');
        if (empty($setting)) {
            return $default;
        }
        return array_merge(
            $default,
            Json::decode($setting)
        );
    }
}