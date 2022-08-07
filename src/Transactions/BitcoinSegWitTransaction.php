<?php

namespace QXCoin\Pouch\Transactions;

use QXCoin\Pouch\Transactions\BitcoinTransactionInput as Input;
use QXCoin\Pouch\Transactions\BitcoinTransactionOutput as Output;
use QXCoin\Pouch\Utils\Bitcoin;

/**
 * Builder class to create Bitcoin raw SegWit transactions.
 */
final class BitcoinSegWitTransaction
{
    /**
     * @var Input[]
     */
    private array $inputs = [];

    /**
     * @var Output[]
     */
    private array $outputs = [];

    private int $locktime = 0x0;

    private array $witnessFields = [];

    public function setInput(int $index, Input $input): void
    {
        $this->inputs[$index] = $input;
    }

    /**
     * @param Input[] $inputs
     */
    public function setInputs(array $inputs): void
    {
        $this->inputs = $inputs;
    }

    /**
     * @return Input[]
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function getInput(int $index): ?Input
    {
        return $this->inputs[$index];
    }

    public function setOutput(int $index, Output $output): void
    {
        $this->outputs[$index] = $output;
    }

    /**
     * @param Output[] $outputs
     */
    public function setOutputs(array $outputs): void
    {
        $this->outputs = $outputs;
    }

    /**
     * @return Output[]
     */
    public function getOutputs(): array
    {
        return $this->outputs;
    }

    public function getOutput(int $index): ?Output
    {
        return $this->outputs[$index];
    }

    public function setLocktime(int $locktime): void
    {
        $this->locktime = $locktime;
    }

    public function getLocktime(): int
    {
        return $this->locktime;
    }

    public function addWitnessField(int $inputIndex, string $witnessField)
    {
        $this->witnessFields[$inputIndex] = $witnessField;
    }

    /**
     * @return string Hexadecimal representation of the transaction
     */
    public function getHex(): string
    {
        // we have to add 00 witness field for those non-segwit inputs
        foreach ($this->inputs as $i => $input) $this->witnessFields[$i] ??= '00';

        $hex = '';
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex(1), 8, '0', STR_PAD_LEFT));
        $hex .= str_pad(dechex(0), 2, '0', STR_PAD_LEFT);
        $hex .= str_pad(dechex(1), 2, '0', STR_PAD_LEFT);
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex(count($this->inputs)), 2, '0', STR_PAD_LEFT));
        $hex .= join('', array_map(fn (Input $inp) => $inp->getHex(), $this->inputs));
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex(count($this->outputs)), 2, '0', STR_PAD_LEFT));
        $hex .= join('', array_map(fn (Output $out) => $out->getHex(), $this->outputs));
        $hex .= join('', $this->witnessFields);
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex($this->locktime), 8, '0', STR_PAD_LEFT));
        return $hex;
    }

    public function getId(): string
    {
        $tx = new BitcoinTransaction();
        $tx->setInputs($this->inputs);
        $tx->setOutputs($this->outputs);
        $tx->setLocktime($this->locktime);
        return $tx->getId();
    }
}
