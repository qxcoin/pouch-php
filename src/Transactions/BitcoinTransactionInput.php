<?php

namespace QXCoin\Pouch\Transactions;

use InvalidArgumentException;
use QXCoin\Pouch\Utils\Bitcoin;

/**
 * Builder class to create Bitcoin raw transaction inputs.
 */
final class BitcoinTransactionInput
{
    /**
     * @param string $txId Existing transaction ID in hexadecimal.
     */
    private string $txId;

    private int $outputIndex;

    private int $outputValue;

    private string $outputScriptPubKey;

    /**
     * @param string $scriptSig A script that unlocks the input in hexadecimal.
     */
    private string $scriptSig = '';

    private int $sequence = 0xFFFFFFFF;

    public function setTxId(string $txId): void
    {
        if (strlen($txId) !== 64)
            throw new InvalidArgumentException('Invalid TX ID, it must be 32 bytes length');

        $this->txId = $txId;
    }

    public function getTxId(): string
    {
        return $this->txId;
    }

    public function setOutputIndex(int $outputIndex): void
    {
        $this->outputIndex = $outputIndex;
    }

    public function getOutputIndex(): int
    {
        return $this->outputIndex;
    }

    public function setOutputScriptPubKey(string $scriptPubKey): void
    {
        $this->outputScriptPubKey = $scriptPubKey;
    }

    public function getOutputScriptPubKey(): string
    {
        return $this->outputScriptPubKey;
    }

    public function setOutputValue(int $value): void
    {
        $this->outputValue = $value;
    }

    public function getOutputValue(): int
    {
        return $this->outputValue;
    }

    public function setScriptSig(string $scriptSig): void
    {
        $this->scriptSig = $scriptSig;
    }

    public function getScriptSig(): string
    {
        return $this->scriptSig;
    }

    public function setSequence(int $sequence): void
    {
        $this->sequence = $sequence;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @return string Hexadecimal representation of the transaction input
     */
    public function getHex(): string
    {
        $hex = '';
        $hex .= Bitcoin::strToLittleEndian($this->txId);
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex($this->outputIndex), 8, '0', STR_PAD_LEFT));
        $hex .= str_pad(dechex(strlen(hex2bin($this->scriptSig))), 2, '0', STR_PAD_LEFT);
        $hex .= $this->scriptSig;
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex($this->sequence), 8, '0', STR_PAD_LEFT));
        return $hex;
    }
}
