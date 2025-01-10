<?php
declare(strict_types=1);
namespace Module\Auth\Domain\WebAuthn;

use Exception;
use Zodream\Image\QrCode;

/**
 * 2fa 登录
 * @source https://github.com/RobThree/TwoFactorAuth
 */
class TwoFactorAuth {
    private static string $base32Dict = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567=';

    /** @var array<string> */
    private static array $base32;

    /** @var array<string, int> */
    private static array $base32Lookup = [];

    public function __construct(
        private readonly string|null   $issuer = null,
        private readonly int       $digits = 6,
        private readonly int       $period = 30,
        private readonly string $algorithm = 'sha1',
    ) {
        if ($this->digits <= 0) {
            throw new Exception('Digits must be > 0');
        }

        if ($this->period <= 0) {
            throw new Exception('Period must be int > 0');
        }

        self::$base32 = str_split(self::$base32Dict);
        self::$base32Lookup = array_flip(self::$base32);
    }

    /**
     * Create a new secret
     */
    public function createSecret(int $bits = 80): string {
        $secret = '';
        $bytes = (int)ceil($bits / 5);   // We use 5 bits of each byte (since we have a 32-character 'alphabet' / BASE32)
        $rnd = $this->getRandomBytes($bytes);
        for ($i = 0; $i < $bytes; $i++) {
            $secret .= self::$base32[ord($rnd[$i]) & 31];  //Mask out left 3 bits for 0-31 values
        }
        return $secret;
    }

    /**
     * Calculate the code with given secret and point in time
     */
    public function getCode(string $secret, int|null $time = null): string {
        $secretKey = $this->base32Decode($secret);

        $timestamp = "\0\0\0\0" . pack('N*', $this->getTimeSlice($this->getTime($time)));  // Pack time into binary string
        $hashHmac = hash_hmac($this->algorithm, $timestamp, $secretKey, true);             // Hash it with users secret key
        $hashPart = substr($hashHmac, ord(substr($hashHmac, -1)) & 0x0F, 4);               // Use last nibble of result as index/offset and grab 4 bytes of the result
        $value = unpack('N', $hashPart);                                                   // Unpack binary value
        $value = $value[1] & 0x7FFFFFFF;                                                   // Drop MSB, keep only 31 bits

        return str_pad((string)($value % 10 ** $this->digits), $this->digits, '0', STR_PAD_LEFT);
    }

    /**
     * Check if the code is correct. This will accept codes starting from ($discrepancy * $period) sec ago to ($discrepancy * period) sec from now
     */
    public function verifyCode(string $secret, string $code, int $discrepancy = 1,
                               int|null $time = null, int|null &$timeSlice = 0): bool {
        $timestamp = $this->getTime();

        $timeSlice = 0;

        // To keep safe from timing-attacks we iterate *all* possible codes even though we already may have
        // verified a code is correct. We use the timeslice variable to hold either 0 (no match) or the timeslice
        // of the match. Each iteration we either set the timeslice variable to the timeslice of the match
        // or set the value to itself.  This is an effort to maintain constant execution time for the code.
        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $ts = $timestamp + ($i * $this->period);
            $slice = $this->getTimeSlice($ts);
            $timeSlice = $this->codeEquals($this->getCode($secret, $ts), $code) ? $slice : $timeSlice;
        }

        return $timeSlice > 0;
    }

    /**
     * Get data-uri of QRCode
     */
    public function getQRCodeImageAsDataUri(string $label, string $secret,
                                            int $size = 200): string {
        if ($size <= 0) {
            throw new Exception('Size must be > 0');
        }
        $img = new QrCode();
        $img->encode($this->getQRText($label, $secret));
        return $img->toBase64();
    }

    /**
     * Builds a string to be encoded in a QR code
     */
    public function getQRText(string $label, string $secret): string {
        return 'otpauth://totp/' . rawurlencode($label)
            . '?secret=' . rawurlencode($secret)
            . '&issuer=' . rawurlencode((string)$this->issuer)
            . '&period=' . $this->period
            . '&algorithm=' . rawurlencode(strtoupper($this->algorithm))
            . '&digits=' . $this->digits;
    }

    public function getRandomBytes(int $byteCount): string {
        if (function_exists('random_bytes')) {
            return random_bytes($byteCount);
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            return openssl_random_pseudo_bytes($byteCount, $cryptoStrong);
        }
        if (!function_exists('hash')) {
            throw new Exception('Unable to find a suited RNGProvider');
        }
        $result = '';
        $hash = mt_rand();
        for ($i = 0; $i < $byteCount; $i++) {
            $hash = hash($this->algorithm, $hash . mt_rand(), true);
            $result .= $hash[mt_rand(0, strlen($hash) - 1)];
        }
        return $result;
    }

    public function getTime(int|null $time = null): int {
        return $time ?? time();
    }

    /**
     * Timing-attack safe comparison of 2 codes (see http://blog.ircmaxell.com/2014/11/its-all-about-time.html)
     */
    private function codeEquals(string $safe, string $user): bool
    {
        if (function_exists('hash_equals')) {
            return hash_equals($safe, $user);
        }
        // In general, it's not possible to prevent length leaks. So it's OK to leak the length. The important part is that
        // we don't leak information about the difference of the two strings.
        if (strlen($safe) === strlen($user)) {
            $result = 0;
            $strlen = strlen($safe);
            for ($i = 0; $i < $strlen; $i++) {
                $result |= (ord($safe[$i]) ^ ord($user[$i]));
            }
            return $result === 0;
        }
        return false;
    }

    private function getTimeSlice(int|null $time = null, int $offset = 0): int {
        return (int)floor($time / $this->period) + ($offset * $this->period);
    }

    private function base32Decode(string $value): string {
        if ($value === '') {
            return '';
        }

        if (preg_match('/[^' . preg_quote(self::$base32Dict, '/') . ']/', $value) !== 0) {
            throw new Exception('Invalid base32 string');
        }

        $buffer = '';
        foreach (str_split($value) as $char) {
            if ($char !== '=') {
                $buffer .= str_pad(decbin(self::$base32Lookup[$char]), 5, '0', STR_PAD_LEFT);
            }
        }
        $length = strlen($buffer);
        $blocks = trim(chunk_split(substr($buffer, 0, $length - ($length % 8)), 8, ' '));

        $output = '';
        foreach (explode(' ', $blocks) as $block) {
            $output .= chr(bindec(str_pad($block, 8, '0', STR_PAD_RIGHT)));
        }
        return $output;
    }
}