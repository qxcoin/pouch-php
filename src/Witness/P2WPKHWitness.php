<?php

namespace QXCoin\Pouch\Witness;

use LogicException;
use QXCoin\Pouch\Utils\Bitcoin;
use QXCoin\Pouch\Transactions\BitcoinSegWitTransaction as Transaction;
use kornrunner\Secp256k1;
use Mdanter\Ecc\Serializer\Signature\DerSignatureSerializer;

final class P2WPKHWitness
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
        $inputs = $this->transaction->getInputs();
        $outputs = $this->transaction->getOutputs();

        $dataPrevOuts = '';
        foreach ($inputs as $inp) {
            $dataPrevOuts .= substr($inp->getHex(), 0, 64 + 8);
        }
        $hashPrevOuts = Bitcoin::hash256(hex2bin($dataPrevOuts));

        $dataSequence = '';
        foreach ($inputs as $out) {
            $dataSequence .= substr($out->getHex(), -8);
        }
        $hashSequence = Bitcoin::hash256(hex2bin($dataSequence));

        $dataOutputs = '';
        foreach ($outputs as $output) {
            $dataOutputs .= $output->getHex();
        }
        $hashOutputs = Bitcoin::hash256(hex2bin($dataOutputs));

        $input = $inputs[$inputIndex];
        $inputScriptPubKey = $input->getOutputScriptPubKey();

        if (!str_starts_with($inputScriptPubKey, '00') or 44 !== strlen($inputScriptPubKey)) {
            throw new LogicException('Invalid ScriptPubKey provided, must in 0x0014{20-byte-public-key-hash160} format.');
        }

        $outpoint = substr($input->getHex(), 0, 64 + 8);
        $scriptCode = '1976a9' . substr($inputScriptPubKey, 2) . '88ac';
        $amount = Bitcoin::strToLittleEndian(str_pad(dechex($input->getOutputValue()), 16, '0', STR_PAD_LEFT));
        $sequence = substr($input->getHex(), -8);

        $dataPreImage = '';
        $dataPreImage .= substr($this->transaction->getHex(), 0, 8); // version
        $dataPreImage .= $hashPrevOuts;
        $dataPreImage .= $hashSequence;
        $dataPreImage .= $outpoint;
        $dataPreImage .= $scriptCode;
        $dataPreImage .= $amount;
        $dataPreImage .= $sequence;
        $dataPreImage .= $hashOutputs;
        $dataPreImage .= substr($this->transaction->getHex(), -8); // locktime
        $dataPreImage .= '01000000';

        $sigHash = Bitcoin::hash256(hex2bin($dataPreImage));

        $secp256k1 = new Secp256k1();
        $signature = $secp256k1->sign($sigHash, $privateKey);

        $serializer = new DerSignatureSerializer();
        $der = bin2hex($serializer->serialize($signature)) . '01';

        $witnessField = '';
        $witnessField .= '02'; // this indicates number of stack items, which is 2 (DER formatted signature and public key)
        $witnessField .= str_pad(dechex(strlen(hex2bin($der))), 2, '0', STR_PAD_LEFT);
        $witnessField .= $der;
        $witnessField .= str_pad(dechex(strlen(hex2bin($publicKey))), 2, '0', STR_PAD_LEFT);
        $witnessField .= $publicKey;

        $this->transaction->addWitnessField($inputIndex, $witnessField);
    }
}
