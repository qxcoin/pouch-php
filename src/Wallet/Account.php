<?php

namespace QXCoin\Pouch\Wallet;

/**
 * A class to represent BIP44 account segment
 */
final class Account
{
    /**
     * @param string $privateKey Serialized BIP32 private key
     * @param string $publicKey Serialized BIP32 public key
     */
    public function __construct(
        public readonly string $privateKey,
        public readonly string $publicKey,
    ) {
        // pass
    }
}
