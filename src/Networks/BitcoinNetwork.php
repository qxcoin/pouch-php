<?php

namespace QXCoin\Pouch\Networks;

use QXCoin\Pouch\Address\P2PKHAddressGenerator;
use QXCoin\BIP32\BitcoinVersionResolver;
use QXCoin\BIP32\VersionResolverInterface;
use QXCoin\Pouch\PrivateKey\WIFPrivateKeyGenerator;
use QXCoin\Pouch\PublicKey\BitcoinCompressedPublicKeyGenerator;

class BitcoinNetwork implements NetworkInterface
{
    private readonly bool $testnet;

    public function __construct(bool $testnet = false)
    {
        $this->testnet = $testnet;
    }

    public function getBip32PublicVersionBytes(): int
    {
        return $this->testnet ? 0x43587cf : 0x488b21e;
    }

    public function getBip32PrivateVersionBytes(): int
    {
        return $this->testnet ? 0x4358394 : 0x488ade4;
    }

    public function getP2pkhPrefix(): int
    {
        return $this->testnet ? 0x6f : 0x00;
    }

    public function getBip44Purpose(): int
    {
        return 44;
    }

    public function getBip44CoinType(): int
    {
        return 0;
    }

    public function getAddressGenerator(): P2PKHAddressGenerator
    {
        return new P2PKHAddressGenerator($this, $this->getPublicKeyGenerator());
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
