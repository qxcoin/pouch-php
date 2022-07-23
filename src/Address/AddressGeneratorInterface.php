<?php

namespace QXCoin\Pouch\Address;

use GMP;

interface AddressGeneratorInterface
{
    public function generate(GMP $x, GMP $y): string;
}
