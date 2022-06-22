<?php

namespace QXCoin\Pouch\Address;

use GMP;

interface AddressGeneratorInterface
{
    public function generateAddress(GMP $x, GMP $y): string;
}
