<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Exception;
use Module\Auth\Domain\CBOR;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Pem;
use Module\SEO\Domain\Option;
use Zodream\Helpers\BinaryReader;
use Zodream\Helpers\Json;
use Zodream\Helpers\Time;

final class PassKey {
    const REGISTER_KEY = 'passkey:registration';
    const LOGIN_KEY = 'passkey:assertion';

    public static function getRegisterOption(): array {
        $user = auth()->user();
        $challenge = md5(sprintf('%d-%d', Time::millisecond(), $user->id));
        $timeout = 60;
        $key = sprintf('%s-%s', self::REGISTER_KEY, $challenge);
        cache()->set($key, $challenge, $timeout);
        return [
            'challenge' => $challenge,
            'rp' => [
                'name' => Option::value('site_title'),
                'id' => request()->host()
            ],
            'user' => [
                'id' => (string)$user->getIdentity(),
                'name' => $user->email,
                'displayName' => $user->name
            ],
            'pubKeyCredParams' => [[
                'alg' => -7,
                'type' => 'public-key'
            ], [
                'type' => 'public-key',
                'alg' => -257
            ]],
            'timeout' => $timeout * 1000,
            'excludeCredentials' => [],
            'attestation' => 'none',
            'authenticatorSelection' => [
                'authenticatorAttachment' => "platform",
                "residentKey" => "preferred",
                'requireResidentKey' => false,
                'userVerification' => 'preferred'
            ],
            'extensions' => [
                'credProps' => true
            ]
        ];
    }

    public static function register(array $credential): void {
        // "{"type":"webauthn.create","challenge":"AAAAAA","origin":"http://zodream.localhost","crossOrigin":false}"
        $clientDataJSON = Json::decode(base64_decode($credential['clientDataJSON']));
        if ($clientDataJSON['type'] !== 'webauthn.create') {
            throw new \Exception('type is error');
        }
        $challenge = base64_decode($clientDataJSON['challenge']);
        $key = sprintf('%s-%s', self::REGISTER_KEY, $challenge);
        if (!cache()->has($key)) {
            throw new \Exception('challenge is expired');
        }
        // $attestationObject = Json::decode(base64_decode($credential['attestationObject']));
        // dr(base64_decode($credential['attestationObject']));
        $obj = static::parseAuthenticatorData($credential['attestationObject']);
        if (empty($obj) || empty($obj['publicKey'])) {
            throw new Exception('attestation is error');
        }
        self::saveCredential($credential['id'], $obj['publicKey'],
            intval($credential['publicKeyAlgorithm']));
    }

    protected static function saveCredential(string $credentialId, string $publicKey,
                                             int $alg = -7): void {
        $key = Pem::parsePublicKey($publicKey, $alg === -7 ? 2 : 3, false);
        OAuthModel::create([
            'user_id' => auth()->id(),
            'vendor' => OAuthModel::TYPE_WEBAUTHN,
            'identity' => $credentialId,
            'data' => $key,
        ]);
    }

    /**
     * 保存注册数据
     * @return array
     * @throws \Exception
     */
    public static function getLoginOption(): array {
        $challenge = md5(sprintf('%d-%d', Time::millisecond(), self::LOGIN_KEY));
        $timeout = 60;
        $key = sprintf('%s-%s', self::REGISTER_KEY, $challenge);
        cache()->set($key, $challenge, $timeout);
        return [
            'challenge' => $challenge,
            'timeout' => $timeout * 1000,
            'rpId' => request()->host(),
            'allowCredentials' => [],
            'userVerification' => 'preferred'
        ];
    }

    public static function login(array $credential): void {
        // {"type":"webauthn.get","challenge":"LtNTg","origin":"https://webauthn.io","crossOrigin":false,"other_keys_can_be_added_here":"do not compare clientDataJSON against a template. See https://goo.gl/yabPex"}
        $clientDataJSON = Json::decode(base64_decode($credential['clientDataJSON']));
        $challenge = base64_decode($clientDataJSON['challenge']);
        $key = sprintf('%s-%s', self::REGISTER_KEY, $challenge);
        if (!cache()->has($key)) {
            throw new \Exception('challenge is expired');
        }
        $userId = base64_decode($credential['userHandle']);
        $signature = $credential['signature'];
        self::loadCredential(intval($userId), $credential['id'], $signature, $credential);
    }

    /**
     * 验证的登录数据
     * @param int $userId
     * @param string $credentialId
     * @param string $signature
     * @param array $credential
     * @return void
     * @throws \Exception
     */
    protected static function loadCredential(int $userId, string $credentialId,  string $signature, array $credential) {
        $key = OAuthModel::where('user_id', $userId)
            ->where('identity', $credentialId)
            ->where('vendor', OAuthModel::TYPE_WEBAUTHN)
            ->value('data');
        if (empty($key)) {
            throw new \Exception('验证失败');
        }
        $data = CBOR::decodeBase64($credential['authenticatorData']);
        $pkey = openssl_get_publickey($key);
        if (empty($pkey)) {
            throw new Exception('public key is error');
        }
        if (!openssl_verify($data.self::hash(CBOR::decodeBase64($credential['clientDataJSON'])),
            CBOR::decodeBase64($signature), $pkey, \OPENSSL_ALGO_SHA256)) {
            throw new \Exception('signature is error');
        }
        AuthRepository::loginUserId($userId, LoginLogModel::MODE_WEBAUTHN);
    }

    private static function hash(string $val): string {
        return \hash('sha256', $val, true);
    }

    /**
     * 转化 AuthenticatorData
     * @param string $val
     * @return array{rpIdHash: string, isUserPresent: bool, isUserVerified: bool,signCount: int, aaguid: string, credentialId: string, publicKey: string}
     */
    public static function parseAuthenticatorData(string $val): array {
        $data = CBOR::decode($val);
        $reader = new BinaryReader($data['authData']);
        $rpIdHash = $reader->read(32);

        $flags = $reader->readUint8();
        $isUserPresent = ($flags & 0x01) === 0x01; // bit 0: User Present
        $isUserVerified = ($flags & 0x04) === 0x04; // bit 2: User Verified
        $isAttested = ($flags & 0x40) === 0x40; // bit 6: Attested credential data incl.
        $extData = ($flags & 0x80) === 0x80; // bit 7: Extension data incl.

        $signCount = $reader->readUint32();

        $authData = compact('rpIdHash', 'isUserPresent', 'isUserVerified', 'signCount');

        if ($isAttested) {
            // https://www.w3.org/TR/2019/REC-webauthn-1-20190304/#sec-attested-credential-data
            $aaguid = $reader->read(16);
            $credentialIdLength = $reader->readUint16();
            $credentialId = $reader->read($credentialIdLength);

            $rawCredentialPublicKey = $reader->readRemaining();

            $authData['aaguid'] = $aaguid;
            $authData['credentialId'] = $credentialId;
            $authData['publicKey'] = $rawCredentialPublicKey;
        }
        if ($extData) {
            // TODO
        }

        return $authData;
    }

}