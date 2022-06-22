<?php

namespace QXCoin\Pouch\PrivateKey;

use GMP;

interface PrivateKeyGeneratorInterface
{
    /**
     * @return string Hexadecimal string
     */
    public function generatePrivateKey(GMP $secret): string;
}
