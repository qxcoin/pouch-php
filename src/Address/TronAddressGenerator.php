<?php

namespace QXCoin\Pouch\Address;

use GMP;
use kornrunner\Keccak;
use QXCoin\Pouch\PublicKey\RawPublicKeyGenerator;
use QXCoin\Pouch\Utils\TronBase58check;

final class TronAddressGenerator implements AddressGeneratorInterface
{
    private RawPublicKeyGenerator $publicKeyGenerator;
    private TronBase58check $base58;

    public function __construct(RawPublicKeyGenerator $publicKeyGenerator)
    {
        $this->publicKeyGenerator = $publicKeyGenerator;

        $this->base58 = new TronBase58check();
    }

    public function generate(GMP $x, GMP $y): string
    {
        $publicKey = $this->publicKeyGenerator->generate($x, $y);

        $step1 = Keccak::hash(hex2bin($publicKey), 256, true);
        $step2 = substr($step1, -20);
        $base58 = $this->base58->encode($step2);

        return $base58;
    }
}
