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
    public static function reader() : null|Reader {
        $file = app_path('data/Country.mmdb');
        if ($file->exist()) {
            return new Reader((string)$file);
        }
        return null;
    }

    public static function find(string $ip) {
        $reader = static::reader();
        if (!$reader) {
            return null;
        }
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
        if (!$reader) {
            return false;
        }
        $data = $reader->get($ip);
        $reader->close();
        return $data['country']['iso_code'] === 'CN';
    }

    public static function country(string $ip): string {
        $reader = static::reader();
        if (!$reader) {
            return 'Unknown';
        }
        $data = $reader->get($ip);
        $reader->close();
        return $data['country']['iso_code']??'Unknown';
    }
}