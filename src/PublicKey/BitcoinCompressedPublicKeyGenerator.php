<?php

namespace QXCoin\Pouch\PublicKey;

use GMP;

final class BitcoinCompressedPublicKeyGenerator implements PublicKeyGeneratorInterface
{
    public function generate(GMP $x, GMP $y): string
    {
        $prefix = gmp_cmp(gmp_mod($y, 2), 0) === 0 ? "02" : "03";

        // sometimes X is not 64 bits longs
        // NOTE: converting back from number to hex causes this, because numbers don't have leading zeroes
        $xHex = str_pad(gmp_strval($x, 16), 64, '0', STR_PAD_LEFT);

        return $prefix . $xHex;
    }
}
