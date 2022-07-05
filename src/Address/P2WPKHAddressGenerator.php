<?php

namespace QXCoin\Pouch\Address;

use GMP;
use QXCoin\Pouch\Networks\BitcoinSegWitNetwork;
use QXCoin\Pouch\PublicKey\BitcoinCompressedPublicKeyGenerator;

use function BitWasp\Bech32\encodeSegwit;

final class P2WPKHAddressGenerator implements AddressGeneratorInterface
{
    private BitcoinSegWitNetwork $network;
    private BitcoinCompressedPublicKeyGenerator $publicKeyGenerator;

    public function __construct(BitcoinSegWitNetwork $network, BitcoinCompressedPublicKeyGenerator $publicKeyGenerator)
    {
        $this->network = $network;
        $this->publicKeyGenerator = $publicKeyGenerator;
    }

    public function generateAddress(GMP $x, GMP $y): string
    {
        $publicKey = $this->publicKeyGenerator->generatePublicKey($x, $y);

        $hash160 = hash('ripemd160', hash('sha256', hex2bin($publicKey), true), true);

        return encodeSegwit($this->network->getP2wpkhPrefix(), 0, $hash160);
    }
}
