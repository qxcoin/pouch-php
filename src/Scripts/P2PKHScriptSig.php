<?php

namespace QXCoin\Pouch\Scripts;

use QXCoin\Pouch\Transactions\BitcoinTransaction as Transaction;
use kornrunner\Secp256k1;
use Mdanter\Ecc\Serializer\Signature\DerSignatureSerializer;
use QXCoin\Pouch\Utils\Bitcoin;

final class P2PKHScriptSig
{
    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function apply(
        int $inputIndex,
        string $privateKey,
        string $publicKey,
    ): void {
        // we need to remove scripts from all other inputs temporarily
        $otherScripts = [];
        foreach ($this->transaction->getInputs() as $i => $input) {
            if ($i === $inputIndex) continue;
            $otherScripts[$i] = $input->getScriptSig();
            $input->setScriptSig('');
        }

        $input = $this->transaction->getInput($inputIndex);

        $input->setScriptSig($input->getOutputScriptPubKey());

        $message = $this->transaction->getResult() . '01000000';

        $hash256 = Bitcoin::hash256(hex2bin($message));

        $secp256k1 = new Secp256k1();
        $signature = $secp256k1->sign($hash256, $privateKey);

        $serializer = new DerSignatureSerializer();
        $der = bin2hex($serializer->serialize($signature)) . '01';

        $scriptSig = '';
        $scriptSig .= str_pad(dechex(strlen(hex2bin($der))), 2, '0', STR_PAD_LEFT);
        $scriptSig .= $der;
        $scriptSig .= str_pad(dechex(strlen(hex2bin($publicKey))), 2, '0', STR_PAD_LEFT);
        $scriptSig .= $publicKey;

        $input->setScriptSig($scriptSig);

        // now that we are done signing, we can restore other scripts
        foreach ($otherScripts as $i => $script) {
            $this->transaction->getInput($i)->setScriptSig($script);
        }
    }
}
