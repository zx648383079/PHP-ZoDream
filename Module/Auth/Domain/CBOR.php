<?php
declare(strict_types=1);
namespace Module\Auth\Domain;

use Zodream\Helpers\BinaryReader;
use Zodream\Infrastructure\Error\StopException;

class CBOR {
    public static function decode(string $val): mixed {
        $reader = new BinaryReader(base64_decode($val));
        self::decodeCborItem($reader);
        return self::decodeCborItem($reader);
    }

    private static function decodeCborItem(BinaryReader $reader): mixed {
        $code = $reader->readUint8();
        if ($code === 0xff) {
            throw new StopException();
        }
        $majorType = $code >> 5;
        $addtlInfo = $code & 0b11111;
        return match ($majorType) {
            0 => self::decodeUnsignedInteger($reader, $addtlInfo), // MT_UINT
            1 => self::decodeNegativeInteger($reader, $addtlInfo), // MT_NINT
            2 => self::decodeBinaryString($reader, $addtlInfo), // MT_BYTESTRING
            3 => self::decodeText($reader, $addtlInfo), // MT_TEXT
            4 => self::decodeArray($reader, $addtlInfo),  // MT_ARRAY
            5 => self::decodeMap($reader, $addtlInfo),   // MT_MAP
            6 => self::decodeTag($reader, $addtlInfo), // MT_TAG
            7 => self::decodeSimple($reader, $addtlInfo), // MT_SIMPLE_AND_FLOAT
            default => throw new Exception('Invalid major type')
        };
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
            throw new OutOfBoundsException((string)$info);
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
                    $ret .= self::decodeCborItem($reader);
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
                    $ret .= self::decodeCborItem($reader);
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
                    $ret[] = self::decodeCborItem($reader);
                } catch (StopException) {
                    return $ret;
                }
            }
        }
        $numItems = self::decodeUnsignedInteger($reader, $addtlInfo);
        for ($i = 0; $i < $numItems; $i++) {
            $ret[] = self::decodeCborItem($reader);
        }
        return $ret;
    }

    private static function decodeMap(BinaryReader $reader, int $addtlInfo): array {
        $ret = [];
        if ($addtlInfo === 31) {
            while (true) {
                try {
                    $key = self::decodeCborItem($reader);
                    $ret[$key] = self::decodeCborItem($reader);
                } catch (StopException $e) {
                    return $ret;
                }
            }
        }
        $numItems = self::decodeUnsignedInteger($reader, $addtlInfo);
        for ($i = 0; $i < $numItems; $i++) {
            $key = self::decodeCborItem($reader);
            $ret[$key] = self::decodeCborItem($reader);
        }
        return $ret;
    }

    /**
     * @see 2.4
     */
    private static function decodeTag(BinaryReader $reader, int $addtlInfo) {
        $tagId = self::decodeUnsignedInteger($reader, $addtlInfo);
        $tag = self::decodeCborItem($reader);
        switch ($tagId) {
            case 2: // postive bignum
                return self::decodeBigint($tag);
            case 3: // negative bignum
                $positive = self::decodeBigint($tag);
                return bcsub('-1', $positive);
        }
        throw new OutOfBoundsException(bin2hex($tag));
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
                return self::readHalfPrecisionFloat($reader);
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

    // Adapted from RFC7049 Appendix D
    private static function readHalfPrecisionFloat(BinaryReader $reader): float {
        $bytes = $reader->read(2);
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