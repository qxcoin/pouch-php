<?php

namespace QXCoin\Pouch\Signers;

use kornrunner\Secp256k1;
use kornrunner\Signature\Signature;

final class TronTransactionSigner
{
    /**
     * @return string Signature in hexadecimal.
     */
    public function sign(string $txId, string $privateKey): string
    {
        $secp256k1 = new Secp256k1();
        /** @var Signature $sign */
        $sign = $secp256k1->sign($txId, $privateKey, ['canonical' => false]);

        return $sign->toHex() . bin2hex(join('', array_map('chr', [$sign->getRecoveryParam()])));
    }
}
