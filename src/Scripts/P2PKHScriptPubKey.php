<?php

namespace QXCoin\Pouch\Scripts;

use QXCoin\Pouch\Transactions\BitcoinTransactionInterface as TransactionInterface;
use QXCoin\Pouch\Utils\Bitcoin;

final class P2PKHScriptPubKey
{
    private TransactionInterface $transaction;

    public function __construct(TransactionInterface $transaction)
    {
        $this->transaction = $transaction;
    }

    public function apply(int $outputIndex, string $publicKey): void
    {
        $hash160 = Bitcoin::hash160(hex2bin($publicKey));

        $hex = '';
        $hex .= '76'; // OP_DUP
        $hex .= 'a9'; // OP_HASH160
        $hex .= str_pad(dechex(strlen(hex2bin($hash160))), 2, '0', STR_PAD_LEFT); // OP_PUSHBYTES_20
        $hex .= $hash160;
        $hex .= '88'; // OP_EQUALVERIFY
        $hex .= 'ac'; // OP_CHECKSIG

        $output = $this->transaction->getOutput($outputIndex);
        $output->setScriptPubKey($hex);
    }
}
