# QZ Tray message-signing deployment

This integration signs QZ API requests on the Laravel server. The private key must never be copied to
the browser, tablet, public web directory, or Windows print client.

## Server files

Recommended locations:

```text
/var/www/received-goods/storage/app/qz/digital-certificate.txt
/var/www/received-goods/storage/app/qz/private-key.pem
```

Add the absolute paths to the production `.env`:

```dotenv
QZ_CERTIFICATE_PATH=/var/www/received-goods/storage/app/qz/digital-certificate.txt
QZ_PRIVATE_KEY_PATH=/var/www/received-goods/storage/app/qz/private-key.pem
QZ_SIGNATURE_ALGORITHM=SHA512
```

Grant the PHP-FPM user read access while keeping the private key restricted:

```bash
sudo chown www-data:www-data storage/app/qz/private-key.pem storage/app/qz/digital-certificate.txt
sudo chmod 600 storage/app/qz/private-key.pem
sudo chmod 644 storage/app/qz/digital-certificate.txt
```

Replace `www-data` when the production PHP process runs as another account.

## Validate the certificate and key

```bash
openssl x509 -in storage/app/qz/digital-certificate.txt -noout -subject -issuer -dates -fingerprint -sha256
openssl pkey -in storage/app/qz/private-key.pem -check -noout
openssl x509 -in storage/app/qz/digital-certificate.txt -pubkey -noout | openssl sha256
openssl pkey -in storage/app/qz/private-key.pem -pubout | openssl sha256
```

The final two SHA-256 values must match.

## Deploy

```bash
php artisan optimize:clear
php artisan config:cache
npm ci
npm run build
```

Reload PHP-FPM when applicable. Use the installed PHP version in the service name.

```bash
sudo systemctl reload php8.2-fpm
```

## Runtime flow

1. An authenticated browser requests `GET /qz/certificate`.
2. QZ supplies each API payload to the browser's signature callback.
3. The browser sends that exact string to authenticated `POST /qz/sign`.
4. Laravel signs it with RSA/SHA-512 and returns only the Base64 signature.
5. QZ Tray validates the signature and performs the requested operation.

## Trace a failure

1. Browser Network panel: `/qz/certificate` and `/qz/sign` must return HTTP 200.
2. HTTP 401/302: the web session expired; sign in again.
3. HTTP 419: CSRF/session cookie mismatch; verify the application URL, HTTPS, and cookie settings.
4. HTTP 422: the signature request was missing or invalid.
5. HTTP 429: the per-user signing rate limit was exceeded.
6. HTTP 500: inspect `storage/logs/laravel.log` for unreadable files, invalid keys, or OpenSSL errors.
7. Both endpoints return 200 but QZ reports an invalid signature: compare the public-key hashes above,
   verify `SHA512` is configured on both sides, and verify Windows trusts the same certificate.
8. Signature is valid but a prompt remains: remove stale certificate decisions from QZ Site Manager,
   allow the current `digital-certificate.txt`, restart QZ Tray, and test again.

The barcode TSPL layout and printer calibration are independent of message signing and are not changed
by this integration.
