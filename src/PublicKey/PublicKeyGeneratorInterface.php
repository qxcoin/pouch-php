<?php

namespace QXCoin\Pouch\PublicKey;

use GMP;

interface PublicKeyGeneratorInterface
{
    /**
     * @return string Hexadecimal string
     */
    public function generate(GMP $x, GMP $y): string;
}
