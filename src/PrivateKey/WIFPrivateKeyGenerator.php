<?php

namespace QXCoin\Pouch\PrivateKey;

use GMP;
use QXCoin\Pouch\Utils\Bitcoin;
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

    public function generate(GMP $secret): string
    {
        $versionByte = pack('C', $this->testnet ? 0xEF : 0x80);
        $key = hex2bin(str_pad(gmp_strval($secret, 16), 64, '0', STR_PAD_LEFT));
        $compressionByte = pack('C', 0x01);

        $hash256 = Bitcoin::hash256($versionByte . $key . $compressionByte, true);
        $checksum = substr($hash256, 0, 4);
        $encoded = $this->base58->encode($versionByte . $key . $compressionByte . $checksum);

        return $encoded;
    }
}
