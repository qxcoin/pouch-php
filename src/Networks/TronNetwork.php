<?php

namespace QXCoin\Pouch\Networks;

use QXCoin\BIP32\BitcoinVersionResolver;
use QXCoin\Pouch\Address\P2WPKHAddressGenerator;
use QXCoin\BIP32\VersionResolverInterface;
use QXCoin\Pouch\Address\TronAddressGenerator;
use QXCoin\Pouch\PrivateKey\TronPrivateKeyGenerator;
use QXCoin\Pouch\PrivateKey\WIFPrivateKeyGenerator;
use QXCoin\Pouch\PublicKey\BitcoinCompressedPublicKeyGenerator;
use QXCoin\Pouch\PublicKey\TronPublicKeyGenerator;

class TronNetwork implements NetworkInterface
{
    public function getBip32VersionResolver(): VersionResolverInterface
    {
        return new BitcoinVersionResolver();
    }

    public function getBip44Purpose(): int
    {
        return 44;
    }

    public function getBip44CoinType(): int
    {
        return 195;
    }

    public function getAddressGenerator(): TronAddressGenerator
    {
        return new TronAddressGenerator($this->getPublicKeyGenerator());
    }

    public function getPublicKeyGenerator(): TronPublicKeyGenerator
    {
        return new TronPublicKeyGenerator();
    }

    public function getPrivateKeyGenerator(): TronPrivateKeyGenerator
    {
        return new TronPrivateKeyGenerator();
    }
}
