<?php

namespace QXCoin\Pouch\Networks;

use QXCoin\Pouch\Address\P2WPKHAddressGenerator;
use QXCoin\Pouch\PrivateKey\WIFPrivateKeyGenerator;
use QXCoin\Pouch\PublicKey\BitcoinCompressedPublicKeyGenerator;

final class BitcoinSegWitNetwork implements NetworkInterface
{
    private readonly bool $testnet;

    public function __construct(bool $testnet = false)
    {
        $this->testnet = $testnet;
    }

    public function getBip32PublicVersionBytes(): int
    {
        return $this->testnet ? 0x045f1cf6 : 0x04b24746;
    }

    public function getBip32PrivateVersionBytes(): int
    {
        return $this->testnet ? 0x045f18bc : 0x04b2430c;
    }

    public function getP2wpkhPrefix(): string
    {
        return $this->testnet ? 'tb' : 'bc';
    }

    public function getBip44Purpose(): int
    {
        return 84;
    }

    public function getBip44CoinType(): int
    {
        return 0;
    }

    public function getAddressGenerator(): P2WPKHAddressGenerator
    {
        return new P2WPKHAddressGenerator($this, $this->getPublicKeyGenerator());
    }

    public function getPublicKeyGenerator(): BitcoinCompressedPublicKeyGenerator
    {
        return new BitcoinCompressedPublicKeyGenerator();
    }

    public function getPrivateKeyGenerator(): WIFPrivateKeyGenerator
    {
        return new WIFPrivateKeyGenerator();
    }
}
