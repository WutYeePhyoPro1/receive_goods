<?php

namespace Tests\Unit;

use App\Services\QzMessageSigner;
use Tests\TestCase;

class QzMessageSignerTest extends TestCase
{
    private string $temporaryDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->temporaryDirectory = sys_get_temp_dir().'/qz-signer-'.bin2hex(random_bytes(6));
        mkdir($this->temporaryDirectory, 0700, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->temporaryDirectory.'/*') ?: [] as $file) {
            unlink($file);
        }

        if (is_dir($this->temporaryDirectory)) {
            rmdir($this->temporaryDirectory);
        }

        parent::tearDown();
    }

    public function test_it_signs_a_request_with_sha512(): void
    {
        $privateKey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);
        self::assertNotFalse($privateKey);

        openssl_pkey_export($privateKey, $privateKeyPem);
        $privateKeyPath = $this->temporaryDirectory.'/private-key.pem';
        file_put_contents($privateKeyPath, $privateKeyPem);

        config([
            'qz.private_key_path' => $privateKeyPath,
            'qz.signature_algorithm' => 'SHA512',
        ]);

        $request = '{"call":"qz.print","params":[]}';
        $signature = (new QzMessageSigner)->sign($request);
        $details = openssl_pkey_get_details($privateKey);

        self::assertSame(
            1,
            openssl_verify($request, base64_decode($signature, true), $details['key'], OPENSSL_ALGO_SHA512)
        );
    }

    public function test_it_rejects_an_unreadable_private_key(): void
    {
        config(['qz.private_key_path' => $this->temporaryDirectory.'/missing.pem']);

        $this->expectExceptionMessage('QZ private key file is missing or unreadable.');

        (new QzMessageSigner)->sign('request');
    }
}
