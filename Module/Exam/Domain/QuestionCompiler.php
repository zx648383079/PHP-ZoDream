<?php
declare(strict_types=1);
namespace Module\Exam\Domain;

use Zodream\Helpers\Str;

class QuestionCompiler {
    public static function generate($content) {
        if (empty($content)) {
            return [];
        }
        $data = [];
        foreach (explode("\n", $content) as $line) {
            $items = explode('=', trim($line), 2);
            if (count($items) < 2) {
                continue;
            }
            $data[$items[0]] = self::compilerValue($items[1], $data);
        }
        return $data;
    }

    public static function strReplace($val, array $data) {
        if (empty($data) || empty($val)) {
            return $val;
        }
        return strtr($val, $data);
    }

    public static function compilerValue($str, $data) {
        if (is_numeric($str)) {
            return $str;
        }
        if (preg_match('/^(.+?)([\>\<\=\!]{1,3})(.+?)\?(.+?):(.+?)$/', $str, $match)) {
            $match[1] = trim($match[1]);
            $match[3] = trim($match[3]);
            if (isset($data[$match[1]])) {
                $match[1] = $data[$match[1]];
            }
            if (isset($data[$match[3]])) {
                $match[3] = $data[$match[3]];
            }
            return self::compilerCon($match[1], $match[2], $match[3])
                ? self::compilerValue($match[4], $data) : self::compilerValue($match[5], $str);
        }
        if (preg_match('/^(.+?)([\+\-*\/]{1,3})(.+?)$/', $str, $match)) {
            $match[1] = trim($match[1]);
            $match[3] = trim($match[3]);
            if (isset($data[$match[1]])) {
                $match[1] = $data[$match[1]];
            }
            if (isset($data[$match[3]])) {
                $match[3] = $data[$match[3]];
            }
            return self::compilerCon($match[1], $match[2], $match[3]);
        }
        if (strpos($str, '...') > 0) {
            return Str::randomInt(...explode('...', $str));
        }
        $items = explode(',', $str);
        if (count($items) === 1) {
            return trim($str);
        }
        return trim($items[Str::randomInt(0, count($items) - 1)]);
    }

    public static function compilerCon($arg, $con, $val) {
        if ($con === '>') {
            return $arg > $val;
        }
        if ($con === '>=') {
            return $arg >= $val;
        }
        if ($con === '<') {
            return $arg < $val;
        }
        if ($con === '<=') {
            return $arg < $val;
        }
        if ($con === '<>' || $con === '!=') {
            return $arg != $val;
        }
        if ($con === '==' || $con === '===') {
            return $arg == $val;
        }
        if ($con === '+') {
            return $arg + $val;
        }
        if ($con === '-') {
            return $arg - $val;
        }
        if ($con === '*') {
            return $arg * $val;
        }
        if ($con === '/') {
            return $arg / $val;
        }
        return false;
    }

    public static function intToChr($int) {
        $str = '';
        if ($int > 26) {
            $str .= self::intToChr(floor($int / 26));
        }
        return $str . chr($int % 26 + 64);
    }
}