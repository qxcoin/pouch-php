<?php

namespace QXCoin\Pouch\Address;

use GMP;
use kornrunner\Keccak;
use QXCoin\Pouch\PublicKey\RawPublicKeyGenerator;
use Tuupola\Base58;

final class TronAddressGenerator implements AddressGeneratorInterface
{
    private RawPublicKeyGenerator $publicKeyGenerator;
    private Base58 $base58;

    public function __construct(RawPublicKeyGenerator $publicKeyGenerator)
    {
        $this->publicKeyGenerator = $publicKeyGenerator;

        $this->base58 = new Base58([
            'characters' => Base58::BITCOIN,
        ]);
    }

    public function generate(GMP $x, GMP $y): string
    {
        $publicKey = $this->publicKeyGenerator->generate($x, $y);

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
