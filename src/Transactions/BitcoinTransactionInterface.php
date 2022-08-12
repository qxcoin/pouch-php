<?php

namespace QXCoin\Pouch\Transactions;

use QXCoin\Pouch\Transactions\BitcoinTransactionInput as Input;
use QXCoin\Pouch\Transactions\BitcoinTransactionOutput as Output;

interface BitcoinTransactionInterface
{
    public function setInput(Input $input, ?int $index = null): void;
    public function setInputs(array $inputs): void;
    public function getInputs(): array;
    public function getInput(int $index): ?Input;

    public function setOutput(Output $output, ?int $index = null): void;
    public function setOutputs(array $outputs): void;
    public function getOutputs(): array;
    public function getOutput(int $index): ?Output;
    public function setLocktime(int $locktime): void;
    public function getLocktime(): int;

    public function getHex(): string;
    public function getId(): string;
}
