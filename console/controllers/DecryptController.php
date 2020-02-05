<?php

namespace console\controllers;


use yii\console\Controller;

class  DecryptController extends Controller
{
    public function actionIndex()
    {
        $f = fopen('php://stdin', 'r');
        while ($line = fgets($f)) {
            $line = trim($line);
            @$raw = hex2bin($line);
            if (empty($raw)) {
                echo openssl_decrypt($line, 'AES-256-CBC', hex2bin('dc6c49aa36f6d1e2b2f31aa3db948605c0f19f6cec169e4b0eb2472aa7841618'), 0, hex2bin('5fcda736ec349d9a1acfd1763bc64e0b')), PHP_EOL;
            } else {
                echo openssl_decrypt($raw, 'AES-128-CBC', 'axb2c3e4f5$6e7%8', OPENSSL_RAW_DATA, 'a1b2c3d4e5f6g7h8'), PHP_EOL;
            }
        }
    }
}
