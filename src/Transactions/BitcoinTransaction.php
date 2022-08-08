<?php

namespace QXCoin\Pouch\Transactions;

use QXCoin\Pouch\Transactions\BitcoinTransactionInput as Input;
use QXCoin\Pouch\Transactions\BitcoinTransactionOutput as Output;
use QXCoin\Pouch\Utils\Bitcoin;

/**
 * Builder class to create Bitcoin raw transactions.
 */
final class BitcoinTransaction
{
    /**
     * @var Input[]
     */
    private array $inputs;

    /**
     * @var Output[]
     */
    private array $outputs;

    private int $locktime = 0x0;

    public function setInput(Input $input, ?int $index = null): void
    {
        if (isset($index)) {
            $this->inputs[$index] = $input;
        } else {
            $this->inputs[] = $input;
        }
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

    public function setOutput(Output $output, ?int $index = null): void
    {
        if (isset($index)) {
            $this->outputs[$index] = $output;
        } else {
            $this->outputs[] = $output;
        }
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

    /**
     * @return string Hexadecimal representation of the transaction
     */
    public function getHex(): string
    {
        $hex = '';
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex(1), 8, '0', STR_PAD_LEFT));
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex(count($this->inputs)), 2, '0', STR_PAD_LEFT));
        $hex .= join('', array_map(fn (Input $inp) => $inp->getHex(), $this->inputs));
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex(count($this->outputs)), 2, '0', STR_PAD_LEFT));
        $hex .= join('', array_map(fn (Output $out) => $out->getHex(), $this->outputs));
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex($this->locktime), 8, '0', STR_PAD_LEFT));
        return $hex;
    }

    public function getId(): string
    {
        return Bitcoin::strToLittleEndian(Bitcoin::hash256(hex2bin($this->getHex())));
    }
}
