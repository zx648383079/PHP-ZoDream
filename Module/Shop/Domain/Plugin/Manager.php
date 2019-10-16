<?php
namespace Module\Shop\Domain\Plugin;

use Zodream\Disk\File;
use Zodream\Helpers\Str;
use Exception;
use Zodream\Service\Factory;

/**
 * Class Manager
 * @package Module\Shop\Domain\Plugin
 * @method static shipping(string $code, $configs = []): BaseShipping
 * @method static payment(string $code, $configs = []): BasePayment
 * @method static oauth(string $code, $configs = []): BaseOAuth
 */
class Manager {
    const NAME_MAP = [
            'oauth' => 'Module\Shop\Domain\Plugin\OAuth\\',
            'payment' => 'Module\Shop\Domain\Plugin\Payment\\',
            'shipping' => 'Module\Shop\Domain\Plugin\Shipping\\'
    ];
    private static $instances = [];

    /**
     * @param $code
     * @param string $type
     * @return BaseShipping|BaseOAuth|BasePayment|mixed
     * @throws Exception
     */
    public function getInstance($code, $type = 'payment') {
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
                __('plugin error')
            );
        }
        return self::$instances[$type][$code] = new $class();
    }

    /**
     * @param string $type
     * @return string[]
     */
    public static function all($type = 'payment') {
        $type = strtolower($type);
        if (!isset(self::NAME_MAP[$type])) {
            return [];
        }
        $data = Factory::root()->directory(self::NAME_MAP[$type])
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
        $code = array_unshift($arguments);
        $instance = self::getInstance($code, $name);
        if (!empty($arguments)) {
            $instance->config(...$arguments);
        }
        return $instance;
    }
}
