<?php

namespace QXCoin\Pouch\Address;

use GMP;
use QXCoin\Pouch\Networks\BitcoinSegWitNetwork;
use QXCoin\Pouch\PublicKey\BitcoinCompressedPublicKeyGenerator;
use QXCoin\Pouch\Utils\Bitcoin;

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

    public function generate(GMP $x, GMP $y): string
    {
        $publicKey = $this->publicKeyGenerator->generate($x, $y);

        $hash160 = Bitcoin::hash160(hex2bin($publicKey), true);

        return encodeSegwit($this->network->getP2wpkhPrefix(), 0, $hash160);
    }
}
