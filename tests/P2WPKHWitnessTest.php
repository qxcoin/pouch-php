<?php

namespace QXCoin\Pouch\Tests;

use QXCoin\Pouch\Witness\P2WPKHWitness;
use QXCoin\Pouch\Transactions\BitcoinSegWitTransaction;
use QXCoin\Pouch\Transactions\BitcoinTransactionInput;
use QXCoin\Pouch\Transactions\BitcoinTransactionOutput;
use PHPUnit\Framework\TestCase;

class P2WPKHWitnessTest extends TestCase
{
    /**
     * @see https://github.com/bitcoin/bips/blob/master/bip-0143.mediawiki#native-p2wpkh
     */
    public function test_can_create_raw_transaction()
    {
        $input0 = new BitcoinTransactionInput();
        $input0->setTxId('9f96ade4b41d5433f4eda31e1738ec2b36f6e7d1420d94a6af99801a88f7f7ff');
        $input0->setOutputIndex(0);
        $input0->setSequence(0xFFFFFFEE);
        $input0->setOutputValue(625000000);
        $input0->setScriptSig('4830450221008b9d1dc26ba6a9cb62127b02742fa9d754cd3bebf337f7a55d114c8e5cdd30be022040529b194ba3f9281a99f2b1c0a19c0489bc22ede944ccf4ecbab4cc618ef3ed01');

        $input1 = new BitcoinTransactionInput();
        $input1->setTxId('8ac60eb9575db5b2d987e29f301b5b819ea83a5c6579d282d189cc04b8e151ef');
        $input1->setOutputIndex(1);
        $input1->setSequence(0xFFFFFFFF);
        $input1->setOutputValue(600000000);
        $input1->setOutputScriptPubKey('00141d0f172a0ecb48aee1be1f2687d2963ae33f71a1');

        $output0 = new BitcoinTransactionOutput();
        $output0->setValue(112340000);
        $output0->setScriptPubKey('76a9148280b37df378db99f66f85c95a783a76ac7a6d5988ac');

        $output1 = new BitcoinTransactionOutput();
        $output1->setValue(223450000);
        $output1->setScriptPubKey('76a9143bde42dbee7e4dbe6a21b2d50ce2f0167faa815988ac');

        $transaction = new BitcoinSegWitTransaction();
        $transaction->setInput(0, $input0);
        $transaction->setInput(1, $input1);
        $transaction->setOutput(0, $output0);
        $transaction->setOutput(1, $output1);
        $transaction->setLocktime(17);

        $p2wpkhWitnessField = new P2WPKHWitness($transaction);
        $p2wpkhWitnessField->apply(
            1,
            '619c335025c7f4012e556c2a58b2506e30b8511b53ade95ea316fd8c3286feb9',
            '025476c2e83188368da1ff3e292e7acafcdb3566bb0ad253f62fc70f07aeee6357',
        );

        $this->assertEquals(
            '01000000000102fff7f7881a8099afa6940d42d1e7f6362bec38171ea3edf433541db4e4ad969f00000000494830450221008b9d1dc26ba6a9cb62127b02742fa9d754cd3bebf337f7a55d114c8e5cdd30be022040529b194ba3f9281a99f2b1c0a19c0489bc22ede944ccf4ecbab4cc618ef3ed01eeffffffef51e1b804cc89d182d279655c3aa89e815b1b309fe287d9b2b55d57b90ec68a0100000000ffffffff02202cb206000000001976a9148280b37df378db99f66f85c95a783a76ac7a6d5988ac9093510d000000001976a9143bde42dbee7e4dbe6a21b2d50ce2f0167faa815988ac000247304402203609e17b84f6a7d30c80bfa610b5b4542f32a8a0d5447a12fb1366d7f01cc44a0220573a954c4518331561406f90300e8f3358f51928d43c212a8caed02de67eebee0121025476c2e83188368da1ff3e292e7acafcdb3566bb0ad253f62fc70f07aeee635711000000',
            $transaction->getResult(),
        );
    }
}
