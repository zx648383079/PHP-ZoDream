<?php
declare(strict_types=1);
namespace Module\Auth\Domain;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use Zodream\Helpers\BinaryReader;
use Zodream\Infrastructure\Error\StopException;

class CBOR {

    public const MAJOR_TYPE_UNSIGNED_INTEGER = 0b000;

    public const MAJOR_TYPE_NEGATIVE_INTEGER = 0b001;

    public const MAJOR_TYPE_BYTE_STRING = 0b010;

    public const MAJOR_TYPE_TEXT_STRING = 0b011;

    public const MAJOR_TYPE_LIST = 0b100;

    public const MAJOR_TYPE_MAP = 0b101;

    public const MAJOR_TYPE_TAG = 0b110;

    public const MAJOR_TYPE_OTHER_TYPE = 0b111;

    public const LENGTH_1_BYTE = 0b00011000;

    public const LENGTH_2_BYTES = 0b00011001;

    public const LENGTH_4_BYTES = 0b00011010;

    public const LENGTH_8_BYTES = 0b00011011;

    public const LENGTH_INDEFINITE = 0b00011111;

    public const FUTURE_USE_1 = 0b00011100;

    public const FUTURE_USE_2 = 0b00011101;

    public const FUTURE_USE_3 = 0b00011110;


    public static function decode(string $val): mixed {
        $reader = new BinaryReader(static::decodeBase64($val));
        return self::process($reader, false);
    }

    public static function decodeByte(string $val): mixed {
        $reader = new BinaryReader($val);
        return self::process($reader, false);
    }

    public static function decodeBase64(string $base64): string {
        static $lookup = [];
        if (empty($lookup)) {
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
            for ($i = 0; $i < strlen($chars); $i ++) {
                $lookup[ord(substr($chars, $i, 1))] = $i;
            }
        }
        $len = strlen($base64);
        $maxLen = (int)floor($len * .75);
        $buffer = [];
        for ($i = 0; $i < $len; $i += 4) {
            $encoded1 = $lookup[ord(substr($base64, $i, 1))];
            $encoded2 = $lookup[ord(substr($base64, $i + 1, 1))];
            $encoded3 = $lookup[ord(substr($base64, $i + 2, 1))];
            $encoded4 = $lookup[ord(substr($base64, $i + 3, 1))];

            $buffer[] = chr(($encoded1 << 2) | ($encoded2 >> 4));
            $buffer[] = chr((($encoded2 & 15) << 4) | ($encoded3 >> 2));
            $buffer[] = chr((($encoded3 & 3) << 6) | ($encoded4 & 63));
        }
        if (count($buffer) > $maxLen) {
            array_splice($buffer, $maxLen);
        }
        return implode('', $buffer);
    }

    private static function process(BinaryReader $stream, bool $breakable = true): mixed {
        $ib = ord($stream->read(1));
        $mt = $ib >> 5;
        $ai = $ib & 0b00011111;
        $val = null;
        switch ($ai) {
            case static::LENGTH_1_BYTE: //24
            case static::LENGTH_2_BYTES: //25
            case static::LENGTH_4_BYTES: //26
            case static::LENGTH_8_BYTES: //27
                $val = $stream->read(2 ** ($ai & 0b00000111));
                break;
            case static::FUTURE_USE_1: //28
            case static::FUTURE_USE_2: //29
            case static::FUTURE_USE_3: //30
                throw new InvalidArgumentException(sprintf(
                    'Cannot parse the data. Found invalid Additional Information "%s" (%d).',
                    str_pad(decbin($ai), 8, '0', STR_PAD_LEFT),
                    $ai
                ));
            case static::LENGTH_INDEFINITE: //31
                return static::processInfinite($stream, $mt, $breakable);
        }

        return static::processFinite($stream, $mt, $ai, $val);
    }

    private static function processFinite(BinaryReader $stream, int $mt, int $ai, ?string $val): mixed {
        switch ($mt) {
            case static::MAJOR_TYPE_UNSIGNED_INTEGER: //0
                return static::parseUnsignedInteger($ai, $val);
            case static::MAJOR_TYPE_NEGATIVE_INTEGER: //1
                return static::parseNegativeInteger($ai, $val);
            case static::MAJOR_TYPE_BYTE_STRING: //2
                $length = $val === null ? $ai : static::parseUnsignedInteger($ai, $val);

                return $stream->read($length);
            case static::MAJOR_TYPE_TEXT_STRING: //3
                $length = $val === null ? $ai : static::parseUnsignedInteger($ai, $val);

                return $stream->read($length);
            case static::MAJOR_TYPE_LIST: //4
                $object = [];
                $nbItems = $val === null ? $ai : static::parseUnsignedInteger($ai, $val);
                for ($i = 0; $i < $nbItems; ++$i) {
                    $object[] = static::process($stream, false);
                }
                return $object;
            case static::MAJOR_TYPE_MAP: //5
                $object = [];
                $nbItems = $val === null ? $ai : static::parseUnsignedInteger($ai, $val);
                for ($i = 0; $i < $nbItems; ++$i) {
                    $object[static::process($stream, false)] = static::process($stream, false);
                }
                return $object;
            case static::MAJOR_TYPE_TAG: //6
                return static::parseTag($stream, $ai, $val);
            case static::MAJOR_TYPE_OTHER_TYPE: //7
                return static::parseSimple($ai, $val);
            default:
                throw new RuntimeException(sprintf(
                    'Unsupported major type "%s" (%d).',
                    str_pad(decbin($mt), 5, '0', STR_PAD_LEFT),
                    $mt
                )); // Should never append
        }
    }

    private static function processInfinite(BinaryReader $stream, int $mt, bool $breakable): mixed {
        switch ($mt) {
            case static::MAJOR_TYPE_BYTE_STRING: //2
                $object = [];
                while (true) {
                    try {
                        $it = static::process($stream, true);
                    } catch (StopException) {
                        break;
                    }
                    if (is_array($it)) {
                        throw new RuntimeException(
                            'Unable to parse the data. Infinite Byte String object can only get Byte String objects.'
                        );
                    }
                    $object[] = $it;
                }
                return implode('', $object);
            case static::MAJOR_TYPE_TEXT_STRING: //3
                $object = [];
                while (true) {
                    try {
                        $it = static::process($stream, true);
                    } catch (StopException) {
                        break;
                    }
                    if (is_array($it)) {
                        throw new RuntimeException(
                            'Unable to parse the data. Infinite Text String object can only get Text String objects.'
                        );
                    }
                    $object[] = $it;
                }

                return $object;
            case static::MAJOR_TYPE_LIST: //4
                $object = [];
                $it = static::process($stream, true);
                while (true) {
                    $object[] = $it;
                    try {
                        $it = static::process($stream, true);
                    } catch (StopException) {
                        break;
                    }
                }

                return $object;
            case static::MAJOR_TYPE_MAP: //5
                $object = [];
                while (true) {
                    try {
                        $it = static::process($stream, true);
                    } catch (StopException) {
                        break;
                    }
                    $object[$it] = static::process($stream, false);
                }

                return $object;
            case static::MAJOR_TYPE_OTHER_TYPE: //7
                if (! $breakable) {
                    throw new InvalidArgumentException('Cannot parse the data. No enclosing indefinite.');
                }

                throw new StopException();
            case static::MAJOR_TYPE_UNSIGNED_INTEGER: //0
            case static::MAJOR_TYPE_NEGATIVE_INTEGER: //1
            case static::MAJOR_TYPE_TAG: //6
            default:
                throw new InvalidArgumentException(sprintf(
                    'Cannot parse the data. Found infinite length for Major Type "%s" (%d).',
                    str_pad(decbin($mt), 5, '0', STR_PAD_LEFT),
                    $mt
                ));
        }
    }

    private static function parseUnsignedInteger(int $ai, ?string $val): int {
        return is_null($val) ? $ai : hexdec(bin2hex($val));
    }

    private static function parseNegativeInteger(int $ai, ?string $val): string {
        return (string)(-1 - (is_null($val) ? $ai : static::parseUnsignedInteger($ai, $val)));
    }

    private static function decodeUnsignedInteger(BinaryReader $reader, int $info): int {
        if ($info <= 23) {
            return $info;
        }
        if ($info === 24) { // 8-bit int
            return $reader->readUint8();
        } elseif ($info === 25) { // 16-bit int
            return $reader->readUint16();
        } elseif ($info === 26) { // 32-bit int
            return $reader->readUint32();
        } elseif ($info === 27) { // 64-bit int
            return $reader->readUint64();
        } else {
            throw new Exception((string)$info);
        }
    }

    private static function decodeNegativeInteger(BinaryReader $reader, int $addtlInfo): int {
        try {
            $uint = self::decodeUnsignedInteger($reader, $addtlInfo);
            return -1 - $uint;
        } catch (\OverflowException $e) {
            throw new \UnderflowException();
        }
    }

    private static function decodeBinaryString(BinaryReader $reader, int $addtlInfo): string {
        if ($addtlInfo === 31) {
            $ret = '';
            while (true) {
                try {
                    $ret .= self::process($reader);
                } catch (StopException) {
                    return $ret;
                }
            }
        }
        $length = self::decodeUnsignedInteger($reader, $addtlInfo);
        return $reader->read($length);
    }

    private static function decodeText(BinaryReader $reader, int $addtlInfo): string {
        if ($addtlInfo === 31) {
            $ret = '';
            while (true) {
                try {
                    $ret .= self::process($reader);
                } catch (StopException) {
                    return $ret;
                }
            }
        }
        $length = self::decodeUnsignedInteger($reader, $addtlInfo);
        return $reader->read($length);
    }

    private static function decodeArray(BinaryReader $reader, int $addtlInfo): array {
        $ret = [];
        if ($addtlInfo === 31) {
            while (true) {
                try {
                    $ret[] = self::process($reader);
                } catch (StopException) {
                    return $ret;
                }
            }
        }
        $numItems = self::decodeUnsignedInteger($reader, $addtlInfo);
        for ($i = 0; $i < $numItems; $i++) {
            $ret[] = self::process($reader);
        }
        return $ret;
    }

    private static function decodeMap(BinaryReader $reader, int $addtlInfo): array {
        $ret = [];
        if ($addtlInfo === 31) {
            while (true) {
                try {
                    $key = self::process($reader);
                    $ret[$key] = self::process($reader);
                } catch (StopException $e) {
                    return $ret;
                }
            }
        }
        $numItems = self::decodeUnsignedInteger($reader, $addtlInfo);
        for ($i = 0; $i < $numItems; $i++) {
            $key = self::process($reader);
            $ret[$key] = self::process($reader);
        }
        return $ret;
    }

    private static function parseTag(BinaryReader $reader, int $ai, string $val) {
        $tagId = static::parseUnsignedInteger($ai, $val);
        $tag = self::process($reader);
        switch ($tagId) {
            case 2: // postive bignum
                return self::decodeBigint($tag);
            case 3: // negative bignum
                $positive = self::decodeBigint($tag);
                return bcsub('-1', $positive);
        }
        throw new Exception(bin2hex($tag));
    }
    private static function decodeTag(BinaryReader $reader, int $addtlInfo) {
        $tagId = self::decodeUnsignedInteger($reader, $addtlInfo);
        $tag = self::process($reader);
        switch ($tagId) {
            case 2: // postive bignum
                return self::decodeBigint($tag);
            case 3: // negative bignum
                $positive = self::decodeBigint($tag);
                return bcsub('-1', $positive);
        }
        throw new Exception(bin2hex($tag));
    }

    /**
     * @return string The bigint value as a string (for bcmath)
     */
    private static function decodeBigint(string $bytes): string {
        $out = '0';
        while (strlen($bytes) > 0) {
            $out = bcmul($out, '256');
            $leadingByte = ord(substr($bytes, 0, 1));
            $out = bcadd($out, (string)$leadingByte);
            $bytes = substr($bytes, 1);
        }
        return $out;
    }

    private static function decodeSimple(BinaryReader $reader, int $info) {
        switch ($info) {
            case 20:
                return false;
            case 21:
                return true;
            case 22:
                return null;
            case 23:
                // undefined
                return null; // PHP does not have separate null and undefined
            case 24:
                $next = $reader->readUint8();
                throw new Exception((string)$next);
            case 25:
                return self::parseHalfPrecisionFloat($reader->read(2));
            case 26:
                return $reader->readFloat();
            case 27:
                return $reader->readDouble();
            case 31:
                // Note: this should be unreachable
                throw new StopException();
            default:
                throw new Exception((string)$info);

        }
    }

    private static function parseSimple(int $info, string $val) {
        switch ($info) {
            case 20:
                return false;
            case 21:
                return true;
            case 22:
                return null;
            case 23:
                // undefined
                return null; // PHP does not have separate null and undefined
            case 24:
                throw new Exception($val);
            case 25:
                return self::parseHalfPrecisionFloat($val);
            case 26:
                return unpack('G', $val)[1];
            case 27:
                return unpack('E', $val)[1];
            case 31:
                // Note: this should be unreachable
                throw new StopException();
            default:
                throw new Exception((string)$info);

        }
    }

    // Adapted from RFC7049 Appendix D
    private static function parseHalfPrecisionFloat(string $bytes): float {
        $half = (ord($bytes[0]) << 8) + ord($bytes[1]);
        $exp = ($half >> 10) & 0x1f;
        $mant = $half & 0x3ff;

        $val = 0;
        if ($exp === 0) {
            $val = self::ldexp($mant, -24);
        } elseif ($exp !== 31) {
            $val = self::ldexp($mant + 1024, $exp - 25);
        } elseif ($mant === 0) {
            $val = \INF;
        } else {
            $val = \NAN;
        }

        return ($half & 0x8000) ? -$val : $val;
    }

    // Adapted from C
    private static function ldexp(float $x, int $exponent): float {
        return $x * pow(2, $exponent);
    }
}