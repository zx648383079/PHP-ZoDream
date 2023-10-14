<?php
declare(strict_types=1);
namespace Module\Auth\Domain;


use Zodream\Helpers\BinaryReader;
use Zodream\Infrastructure\Error\StopException;

class Pem {

    public static function parsePublicKey(string $val, int $type, bool $isEncode = true): string {
        return static::derToPublicKey(static::parseDer($val, $type, $isEncode));
    }

    public static function derToPublicKey(string $val): string {
        $pem  = "-----BEGIN PUBLIC KEY-----\n";
        $pem .= chunk_split(base64_encode($val), 64, "\n");
        $pem .= "-----END PUBLIC KEY-----";
        return $pem;
    }

    public static function parseDer(string $val, int $type, bool $isEncode = true): string {
        $enc = $isEncode ? CBOR::decode($val) : CBOR::decodeByte($val);
        return match ($type) {
            2 => static::parseEC2Der($enc),
            3 => static::parseRSADer($enc),
            default => throw new \Exception('unsupport'),
        };
    }

    public static function parseEC2Der(mixed $enc): string {
        $x = $enc[-2];
        $y = $enc[-3];
        return static::derSequence(
            static::derSequence(
                static::derOid("\x2A\x86\x48\xCE\x3D\x02\x01") . // OID 1.2.840.10045.2.1 ecPublicKey
                static::derOid("\x2A\x86\x48\xCE\x3D\x03\x01\x07")  // 1.2.840.10045.3.1.7 prime256v1
            ) .
            static::derBitString("\x04" . $x . $y)
        );
    }

    public static function parseRSADer(mixed $enc): string {
        $n = $enc[-1];
        $e = $enc[-2];
        return static::derSequence(
            static::derSequence(
                static::derOid("\x2A\x86\x48\x86\xF7\x0D\x01\x01\x01") . // OID 1.2.840.113549.1.1.1 rsaEncryption
                static::derNullValue()
            ) .
            static::derBitString(
                static::derSequence(
                    static::derUnsignedInteger($n) .
                    static::derUnsignedInteger($e)
                )
            )
        );
    }


    protected static function derLength(int $len): string {
        if ($len < 128) {
            return \chr($len);
        }
        $lenBytes = '';
        while ($len > 0) {
            $lenBytes = \chr($len % 256) . $lenBytes;
            $len = \intdiv($len, 256);
        }
        return \chr(0x80 | \strlen($lenBytes)) . $lenBytes;
    }

    protected static function derSequence(string $contents): string {
        return "\x30" . static::derLength(\strlen($contents)) . $contents;
    }

    protected static function derOid(string $encoded): string {
        return "\x06" . static::derLength(\strlen($encoded)) . $encoded;
    }

    protected static function derBitString(string $bytes): string {
        return "\x03" . static::derLength(\strlen($bytes) + 1) . "\x00" . $bytes;
    }

    protected static function derNullValue(): string {
        return "\x05\x00";
    }

    protected static function derUnsignedInteger(string $bytes): string {
        $len = \strlen($bytes);

        // Remove leading zero bytes
        for ($i = 0; $i < ($len - 1); $i++) {
            if (\ord($bytes[$i]) !== 0) {
                break;
            }
        }
        if ($i !== 0) {
            $bytes = \substr($bytes, $i);
        }

        // If most significant bit is set, prefix with another zero to prevent it being seen as negative number
        if ((\ord($bytes[0]) & 0x80) !== 0) {
            $bytes = "\x00" . $bytes;
        }

        return "\x02" . static::derLength(\strlen($bytes)) . $bytes;
    }
}