<?php
namespace Module\Shop\Domain\Plugin;

use Zodream\Disk\File;
use Zodream\Helpers\Str;
use Exception;


/**
 * Class Manager
 * @package Module\Shop\Domain\Plugin
 * @method static BaseShipping shipping(string $code, $configs = [])
 * @method static BasePayment payment(string $code, $configs = [])
 * @method static BaseOAuth oauth(string $code, $configs = [])
 */
class Manager {
    const NAME_MAP = [
        'oauth' => 'Module\Shop\Domain\Plugin\OAuth\\',
        'payment' => 'Module\Shop\Domain\Plugin\Payment\\',
        'shipping' => 'Module\Shop\Domain\Plugin\Shipping\\',
        'activity' => 'Module\Shop\Domain\Plugin\Activity\\',
    ];
    private static array $instances = [];

    /**
     * @param string $code
     * @param string $type
     * @return BaseShipping|BaseOAuth|BasePayment|mixed
     * @throws Exception
     */
    public static function getInstance(string $code, string $type = 'payment') {
        $type = strtolower($type);
        $code = Str::studly($code);
        if (isset(self::$instances[$type][$code])) {
            return self::$instances[$type][$code];
        }
        if (!isset(self::NAME_MAP[$type])) {
            throw new Exception(
                __('plugin error')
            );
        }
        $class = self::NAME_MAP[$type].$code;
        if (!class_exists($class)) {
            throw new Exception(
                __('plugin {code} error', compact('code'))
            );
        }
        return self::$instances[$type][$code] = new $class();
    }

    /**
     * @param string $type
     * @return string[]
     */
    public static function all(string $type = 'payment') {
        $type = strtolower($type);
        if (!isset(self::NAME_MAP[$type])) {
            return [];
        }
        $data = app_path()->directory(self::NAME_MAP[$type])
            ->children();
        $items = [];
        foreach ($data as $item) {
            if ($item instanceof File) {
                $items[] = Str::unStudly($item->getNameWithoutExtension());
            }
        }
        return $items;
    }

    public static function __callStatic($name, $arguments) {
        if (empty($arguments)) {
            throw new Exception(
                __('plugin error')
            );
        }
        $code = array_shift($arguments);
        $instance = self::getInstance($code, $name);
        if (!empty($arguments)) {
            $instance->config(...$arguments);
        }
        return $instance;
    }
}
