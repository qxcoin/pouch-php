<?php

namespace QXCoin\Pouch\Transactions;

use QXCoin\Pouch\Utils\Bitcoin;

/**
 * Builder class to create Bitcoin raw transaction outputs.
 */
final class BitcoinTransactionOutput
{
    /**
     * @param int $value The value of the output in satoshis.
     */
    private int $value;

    /**
     * @param string $scriptPubKey A script that locks the output in hexadecimal.
     */
    private string $scriptPubKey;

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function setScriptPubKey(string $scriptPubKey): void
    {
        $this->scriptPubKey = $scriptPubKey;
    }

    /**
     * @return string Hexadecimal representation of the transaction output
     */
    public function getHex(): string
    {
        $hex = '';
        $hex .= Bitcoin::strToLittleEndian(str_pad(dechex($this->value), 16, '0', STR_PAD_LEFT));
        $hex .= str_pad(dechex(strlen(hex2bin($this->scriptPubKey))), 2, '0', STR_PAD_LEFT);
        $hex .= $this->scriptPubKey;
        return $hex;
    }
}
