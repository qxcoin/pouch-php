<?php

namespace QXCoin\Pouch\PublicKey;

use GMP;

interface PublicKeyGeneratorInterface
{
    /**
     * @return string Hexadecimal string
     */
    public function generatePublicKey(GMP $x, GMP $y): string;
}
