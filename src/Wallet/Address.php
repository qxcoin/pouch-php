<?php

namespace QXCoin\Pouch\Wallet;

/**
 * A generic crypto currency address class to represent all kind of addresses
 * including those generated using BIP44.
 *
 * This is why we store public and private keys as hexadecimal string here.
 * This class has nothing to do with BIP44 or any other strategy.
 */
final class Address
{
    /**
     * @param string $hash Address hash
     * @param string $publicKey Public key in hexadecimal string
     * @param string $privateKey Private key in hexadecimal string
     */
    public function __construct(
        public readonly string $hash,
        public readonly string $publicKey,
        public readonly string $privateKey,
    ) {
        // pass
    }
}
