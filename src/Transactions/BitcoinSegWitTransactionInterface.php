<?php

namespace QXCoin\Pouch\Transactions;

use QXCoin\Pouch\Transactions\BitcoinTransactionInput as Input;
use QXCoin\Pouch\Transactions\BitcoinTransactionOutput as Output;

interface BitcoinSegWitTransactionInterface extends BitcoinTransactionInterface
{
    public function addWitnessField(int $inputIndex, string $witnessField): void;
}
