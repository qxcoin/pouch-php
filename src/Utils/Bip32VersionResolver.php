<?php

namespace QXCoin\Pouch\Utils;

use QXCoin\BIP32\VersionResolverInterface;
use InvalidArgumentException;
use QXCoin\Pouch\Networks\NetworkInterface;

/**
 * An *Adapter* class to convert NetworkInterface to VersionResolverInterface
 * Expected by BIP32 package.
 */
final class Bip32VersionResolver implements VersionResolverInterface
{
    private NetworkInterface $network;

    public function __construct(NetworkInterface $network)
    {
        $this->network = $network;
    }

    public function getPublicVersionBytes(): int
    {
        return $this->network->getBip32PublicVersionBytes();
    }

    public function getPrivateVersionBytes(): int
    {
        return $this->network->getBip32PrivateVersionBytes();
    }

    public function convertVersionBytes(int $bytes): int
    {
        if ($bytes === $this->getPublicVersionBytes()) {
            return $this->getPrivateVersionBytes();
        } elseif ($bytes === $this->getPrivateVersionBytes()) {
            return $this->getPublicVersionBytes();
        } else {
            throw new InvalidArgumentException('Unsupported bytes provided.');
        }
    }
}
