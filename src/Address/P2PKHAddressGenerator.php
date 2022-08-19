<?php

namespace QXCoin\Pouch\Address;

use GMP;
use QXCoin\Pouch\Networks\BitcoinNetwork;
use QXCoin\Pouch\PublicKey\BitcoinCompressedPublicKeyGenerator;
use QXCoin\Pouch\Utils\Bitcoin;
use Tuupola\Base58;

final class P2PKHAddressGenerator implements AddressGeneratorInterface
{
    private BitcoinNetwork $network;
    private BitcoinCompressedPublicKeyGenerator $publicKeyGenerator;
    private Base58 $base58;

    public function __construct(BitcoinNetwork $network, BitcoinCompressedPublicKeyGenerator $publicKeyGenerator)
    {
        $this->network = $network;
        $this->publicKeyGenerator = $publicKeyGenerator;

        $this->base58 = new Base58([
            'characters' => Base58::BITCOIN,
            'check' => true,
            'version' => $this->network->getP2pkhPrefix(),
        ]);
    }

    public function generate(GMP $x, GMP $y): string
    {
        $publicKey = $this->publicKeyGenerator->generate($x, $y);

        $hash160 = Bitcoin::hash160(hex2bin($publicKey), true);

        return $this->base58->encode($hash160);
    }
}
