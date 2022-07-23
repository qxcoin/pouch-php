<?php

namespace QXCoin\Pouch\PrivateKey;

use GMP;

final class RawPrivateKeyGenerator implements PrivateKeyGeneratorInterface
{
    public function generate(GMP $secret): string
    {
        return str_pad(gmp_strval($secret, 16), 64, '0', STR_PAD_LEFT);
    }
}
