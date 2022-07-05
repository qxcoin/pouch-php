<?php

namespace QXCoin\Pouch\Address;

use GMP;
use kornrunner\Keccak;
use QXCoin\Pouch\PublicKey\TronPublicKeyGenerator;
use Tuupola\Base58;

final class TronAddressGenerator implements AddressGeneratorInterface
{
    private TronPublicKeyGenerator $publicKeyGenerator;
    private Base58 $base58;

    public function __construct(TronPublicKeyGenerator $publicKeyGenerator)
    {
        $this->publicKeyGenerator = $publicKeyGenerator;

        $this->base58 = new Base58([
            'characters' => Base58::BITCOIN,
        ]);
    }

    public function generateAddress(GMP $x, GMP $y): string
    {
        $publicKey = $this->publicKeyGenerator->generatePublicKey($x, $y);

        $step3 = Keccak::hash(hex2bin($publicKey), 256, true);
        $step4 = substr($step3, -20);
        $address = pack('C', 0x41) . $step4;
        $step6 = hash('sha256', $address, true);
        $step7 = hash('sha256', $step6, true);
        $checksum = substr($step7, 0, 4);
        $step9 = $address . $checksum;
        $base58 = $this->base58->encode($step9);

        return $base58;
    }
}
