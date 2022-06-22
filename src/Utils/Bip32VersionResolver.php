<?php

namespace QXCoin\Pouch\Utils;

use QXCoin\BIP32\VersionResolverInterface;
use InvalidArgumentException;

class Bip32VersionResolver implements VersionResolverInterface
{
    private readonly int $publicVersionBytes;
    private readonly int $privateVersionBytes;

    public function __construct(int $publicVersionBytes, int $privateVersionBytes)
    {
        $this->publicVersionBytes = $publicVersionBytes;
        $this->privateVersionBytes = $privateVersionBytes;
    }

    public function getPublicVersionBytes(): int
    {
        return $this->publicVersionBytes;
    }

    public function getPrivateVersionBytes(): int
    {
        return $this->privateVersionBytes;
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
