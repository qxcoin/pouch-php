<?php

namespace QXCoin\Pouch\Wallet;

use QXCoin\BIP32\BIP32;
use QXCoin\Pouch\Networks\NetworkInterface;
use QXCoin\Pouch\Utils\Bip32VersionResolver;

class WalletFactory
{
    public function createWallet(string $seed, NetworkInterface $network)
    {
        $versionResolver = new Bip32VersionResolver($network);
        $purpose = $network->getBip44Purpose();
        $coinType = $network->getBip44CoinType();

        $bip32 = new BIP32($versionResolver);

        $masterKey = $bip32->generateMasterKey($seed);
        $walletKey = $bip32->derive($masterKey, "m/{$purpose}'/{$coinType}'");

        return new Wallet(
            $bip32,
            $network->getAddressGenerator(),
            $network->getPublicKeyGenerator(),
            $network->getPrivateKeyGenerator(),
            $walletKey,
        );
    }
}
