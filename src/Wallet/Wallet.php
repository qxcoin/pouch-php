<?php

namespace QXCoin\Pouch\Wallet;

use QXCoin\Pouch\Address\AddressGeneratorInterface;
use QXCoin\BIP32\BIP32;
use QXCoin\BIP32\PrivateChildKey;
use QXCoin\Pouch\PrivateKey\PrivateKeyGeneratorInterface;
use QXCoin\Pouch\PublicKey\PublicKeyGeneratorInterface;

/**
 * A class to generate BIP44 account and addresses
 */
class Wallet
{
    private BIP32 $bip32;
    private AddressGeneratorInterface $addressGenerator;
    private PublicKeyGeneratorInterface $publicKeyGenerator;
    private PrivateKeyGeneratorInterface $privateKeyGenerator;
    private PrivateChildKey $walletKey;

    public function __construct(
        BIP32 $bip32,
        AddressGeneratorInterface $addressGenerator,
        PublicKeyGeneratorInterface $publicKeyGenerator,
        PrivateKeyGeneratorInterface $privateKeyGenerator,
        PrivateChildKey $walletKey,
    ) {
        $this->bip32 = $bip32;
        $this->addressGenerator = $addressGenerator;
        $this->publicKeyGenerator = $publicKeyGenerator;
        $this->privateKeyGenerator = $privateKeyGenerator;
        $this->walletKey = $walletKey;
    }

    public function getAccount(int $index): Account
    {
        $accountPrivateKey = $this->bip32->derive($this->walletKey, "m/{$index}'");
        $accountPublicKey = $this->bip32->derive($accountPrivateKey, "M");

        return new Account(
            $this->bip32->serialize($accountPrivateKey),
            $this->bip32->serialize($accountPublicKey),
        );
    }

    public function getAddress(int $addressIndex, bool $change = false, int|Account $account = 0): Address
    {
        // NOTE: type fix, FALSE value will not auto convert to 0 string
        $change = $change ? '1' : '0';

        if (is_int($account)) $account = $this->getAccount($account);

        $accountKey = $this->bip32->deserialize($account->privateKey);

        $addressPrivateKey = $this->bip32->derive($accountKey, "m/{$change}/{$addressIndex}");
        $addressPublicKey = $this->bip32->derive($addressPrivateKey, "M");

        $hash = $this->addressGenerator->generate($addressPublicKey->x, $addressPublicKey->y);
        $publicKey = $this->publicKeyGenerator->generate($addressPublicKey->x, $addressPublicKey->y);
        $privateKey = $this->privateKeyGenerator->generate($addressPrivateKey->secret);

        return new Address($hash, $publicKey, $privateKey);
    }
}
