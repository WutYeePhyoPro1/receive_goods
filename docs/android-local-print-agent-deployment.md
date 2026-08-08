# Android Local Print Agent deployment

## Architecture

`Received Goods WebView -> AndroidPrintAgent bridge -> Android USB Host -> Gainscha USB printer`

The browser layout and TSPL generation remain in `resources/js/qz-barcode-print.js`. QZ Tray remains
available as the Windows fallback. Android mode converts each already-generated TSPL page to Base64,
passes it to the native bridge, and writes the decoded bytes to USB in 16 KiB chunks.

## Rollout checklist

1. Build and install the APK by following `android-print-agent/README.md`.
2. Confirm the tablet can reach the Received Goods server URL.
3. Connect one printer and record its displayed vendor/product IDs.
4. Allow USB permission and run the three-label test.
5. Physically verify barcode scanning for Bar1, Bar2, and Bar3.
6. Test 1, 3, 4, 6, and 7 labels to cover page boundaries.
7. Disconnect/reconnect USB and verify Detect, permission, and error messages.
8. Keep Browser Print and QZ Direct Print enabled until the Android pilot is accepted.

## Trace path

1. Web UI status: verify Android mode and selected USB device.
2. Browser/WebView console: inspect `Android Print Agent detection failed` or thrown print errors.
3. Android Studio Logcat: filter by application ID `com.kpay.receivedgoods.printagent`.
4. USB enumeration: compare vendor ID, product ID, interface count, and bulk OUT availability.
5. Transport: errors include `printer_not_selected`, `printer_disconnected`, `permission_required`,
   `endpoint_not_found`, `open_failed`, `claim_failed`, and `transfer_failed`.
6. If transport succeeds but output is wrong, inspect TSPL/printer emulation rather than the web layout.

## Known boundary

This first version supports USB printers exposing a standard bulk OUT endpoint. A Gainscha model using a
proprietary USB protocol will require its Android SDK to replace the `bulkTransfer` implementation; the
web payload and UI can remain unchanged.
