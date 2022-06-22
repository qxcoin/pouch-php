<?php

namespace QXCoin\Pouch\Wallet;

use QXCoin\BIP32\BIP32;
use QXCoin\Pouch\Networks\NetworkManager;

class WalletFactory
{
    private NetworkManager $networks;

    public function __construct(NetworkManager $networks)
    {
        $this->networks = $networks;
    }

    public function createWallet(string $seed, string $networkName)
    {
        $network = $this->networks->get($networkName);

        $versionResolver = $network->getBip32VersionResolver();
        $purpose = $network->getBip44Purpose();
        $coinType = $network->getBip44CoinType();

        $bip32 = new BIP32($versionResolver);

        $masterKey = $bip32->generateMasterKey($seed, $networkName);
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
