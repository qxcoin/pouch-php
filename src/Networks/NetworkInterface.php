<?php

namespace QXCoin\Pouch\Networks;

use QXCoin\Pouch\Address\AddressGeneratorInterface;
use QXCoin\BIP32\VersionResolverInterface;
use QXCoin\Pouch\PublicKey\PublicKeyGeneratorInterface;
use QXCoin\Pouch\PrivateKey\PrivateKeyGeneratorInterface;

interface NetworkInterface
{
    public function getBip32VersionResolver(): VersionResolverInterface;
    public function getBip44Purpose(): int;
    public function getBip44CoinType(): int;
    public function getAddressGenerator(): AddressGeneratorInterface;
    public function getPublicKeyGenerator(): PublicKeyGeneratorInterface;
    public function getPrivateKeyGenerator(): PrivateKeyGeneratorInterface;
}
