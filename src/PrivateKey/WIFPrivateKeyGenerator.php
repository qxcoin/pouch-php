<?php

namespace QXCoin\Pouch\PrivateKey;

use GMP;
use Tuupola\Base58;

final class WIFPrivateKeyGenerator implements PrivateKeyGeneratorInterface
{
    private readonly bool $testnet;
    private Base58 $base58;

    public function __construct(bool $testnet = false)
    {
        $this->testnet = $testnet;

        $this->base58 = new Base58([
            'characters' => Base58::BITCOIN,
        ]);
    }

    public function generatePrivateKey(GMP $secret): string
    {
        $versionByte = pack('C', $this->testnet ? 0xEF : 0x80);
        $key = hex2bin(str_pad(gmp_strval($secret, 16), 64, '0', STR_PAD_LEFT));
        $compressionByte = pack('C', 0x01);

        $hash256 = hash('sha256', hash('sha256', $versionByte . $key . $compressionByte, true), true);
        $checksum = substr($hash256, 0, 4);
        $base58 = $this->base58->encode($versionByte . $key . $compressionByte . $checksum);

        return $base58;
    }
}
