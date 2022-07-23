<?php

namespace QXCoin\Pouch\PrivateKey;

use GMP;

interface PrivateKeyGeneratorInterface
{
    /**
     * @return string Hexadecimal string
     */
    public function generate(GMP $secret): string;
}
