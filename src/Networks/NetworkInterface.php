<?php

namespace QXCoin\Pouch\Networks;

use QXCoin\Pouch\Address\AddressGeneratorInterface;
use QXCoin\Pouch\PublicKey\PublicKeyGeneratorInterface;
use QXCoin\Pouch\PrivateKey\PrivateKeyGeneratorInterface;

interface NetworkInterface
{
    public function getBip32PublicVersionBytes(): int;
    public function getBip32PrivateVersionBytes(): int;
    public function getBip44Purpose(): int;
    public function getBip44CoinType(): int;
    public function getAddressGenerator(): AddressGeneratorInterface;
    public function getPublicKeyGenerator(): PublicKeyGeneratorInterface;
    public function getPrivateKeyGenerator(): PrivateKeyGeneratorInterface;
}
