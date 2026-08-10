<?php

namespace App\Services;

use RuntimeException;

class QzMessageSigner
{
    public function certificate(): string
    {
        $path = (string) config('qz.certificate_path');

        if (! is_file($path) || ! is_readable($path)) {
            throw new RuntimeException('QZ certificate file is missing or unreadable.');
        }

        $certificate = file_get_contents($path);

        if ($certificate === false || trim($certificate) === '') {
            throw new RuntimeException('QZ certificate file is empty or unreadable.');
        }

        if (openssl_x509_read($certificate) === false) {
            throw new RuntimeException('QZ certificate is not a valid X.509 certificate.');
        }

        return $certificate;
    }

    public function sign(string $request): string
    {
        $path = (string) config('qz.private_key_path');

        if (! is_file($path) || ! is_readable($path)) {
            throw new RuntimeException('QZ private key file is missing or unreadable.');
        }

        $privateKeyContents = file_get_contents($path);

        if ($privateKeyContents === false || trim($privateKeyContents) === '') {
            throw new RuntimeException('QZ private key file is empty or unreadable.');
        }

        $privateKey = openssl_pkey_get_private($privateKeyContents);

        if ($privateKey === false) {
            throw new RuntimeException('QZ private key is invalid or requires an unsupported passphrase.');
        }

        $signature = '';
        $algorithm = $this->opensslAlgorithm();

        if (! openssl_sign($request, $signature, $privateKey, $algorithm)) {
            throw new RuntimeException('QZ request signing failed.');
        }

        return base64_encode($signature);
    }

    private function opensslAlgorithm(): int
    {
        return match (strtoupper((string) config('qz.signature_algorithm', 'SHA512'))) {
            'SHA256' => OPENSSL_ALGO_SHA256,
            'SHA512' => OPENSSL_ALGO_SHA512,
            default => throw new RuntimeException('Unsupported QZ signature algorithm.'),
        };
    }
}
