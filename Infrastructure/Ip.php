<?php
declare(strict_types=1);
namespace Infrastructure;

use MaxMind\Db\Reader;
/***
 *
 * ip 数据库 https://github.com/Loyalsoldier/geoip
 */
class Ip {

    /***
     * 获取查询器，请记得使用 close 关闭
     * @return Reader
     * @throws Reader\InvalidDatabaseException
     */
    public static function reader() {
        return new Reader((string)app_path('data/Country.mmdb'));
    }

    public static function find(string $ip) {
        $reader = static::reader();
        $data = $reader->get($ip);
        $reader->close();
        return $data;
    }

    /**
     * 根据ip判断是否属于中国，
     * @param string $ip
     * @return bool
     */
    public static function isChina(string $ip): bool {
        $reader = static::reader();
        $data = $reader->get($ip);
        $reader->close();
        return $data['country']['iso_code'] === 'CN';
    }

    public static function country(string $ip): string {
        $reader = static::reader();
        $data = $reader->get($ip);
        $reader->close();
        return $data['country']['iso_code']??'Unknown';
    }
}