# Received Goods – QZ Tray Barcode Deployment

## What this adds

Received Goods now has two barcode print modes:

- **Browser Print (Fallback)** – the existing working browser print flow.
- **QZ Direct Print** – sends 203-DPI TSPL commands to a Gainscha printer connected by USB to the client PC.

The selected mode and printer queue are saved in that browser's `localStorage`. Each client PC can therefore select a different Gainscha model or Windows printer queue.

## Saved fallback branch

The working browser-print version before QZ integration is preserved at:

```text
backup/barcode-browser-print-20260807
```

QZ development is on:

```text
feature/qz-barcode-print
```

## Server deployment

Run these commands from the project directory on the application server:

```bash
git fetch --all
git switch feature/qz-barcode-print
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Confirm that the new Vite manifest and JavaScript asset exist under `public/build`.

No database migration is required. Printer preferences are stored on the client PC, not in the database.

## Client PC installation

Perform this once on every Windows PC that will print Received Goods labels:

1. Confirm that the Gainscha USB driver is installed.
2. Confirm that Windows can print a printer test page.
3. Give each Windows printer queue a clear name, for example:
   - `Received Goods - GE2406T`
   - `Received Goods - GS2406T Plus`
4. Install QZ Tray 2.2.x from the official QZ Tray download page.
5. Enable QZ Tray to start automatically with Windows.
6. Start QZ Tray and confirm its tray icon is visible.
7. Open Received Goods in Edge or Chrome.
8. Open the barcode print modal and choose **QZ Direct Print**.
9. When QZ asks whether the site may connect, allow it and remember the decision.
10. Click **Refresh**, select the required Gainscha queue, and click **Test**.
11. Keep the mode on **QZ Direct Print** after the test label is correct.

The selected queue is saved only for that browser profile on that PC. A different PC or browser profile must select its own printer once.

## Printer stock and calibration

Use the same stock profile on both Gainscha models:

```text
Stock type: Die-Cut Labels
Page size:  4.33in × 1.06in
Metric:     109.982mm × 26.924mm
Resolution: 203 DPI
```

The direct-print TSPL layout uses:

```text
Page:       approximately 879 × 215 dots
Sticker:    approximately 272 × 164 dots
Column gap: approximately 25 dots (3.1mm)
Code 128:   2-dot narrow module
```

Run the printer's media calibration once after loading a new stock roll or when labels feed at the wrong pitch.

## Rollout checklist

Test one printer/model first before enabling every client:

1. Print Bar1 with quantities 1, 3, 4, and 12.
2. Print Bar2 with quantities 1, 3, 6, 7, and 12.
3. Print Bar3 with quantities 1, 3, 4, and 12.
4. Check left, center, and right stickers.
5. Scan every printed barcode, especially Bar2.
6. Test a short and a long product name.
7. Restart Windows and confirm QZ Tray starts automatically.
8. Confirm the saved printer is still selected.
9. Disconnect the printer and confirm Received Goods shows an error instead of recording a successful direct print.
10. Confirm **Browser Print (Fallback)** still works.

Only roll out to the remaining client PCs after the first printer passes this checklist.

## Troubleshooting

### QZ Tray cannot connect

- Check the QZ tray icon is running.
- Restart QZ Tray, then refresh Received Goods.
- Check Edge/Chrome and Windows Firewall did not block localhost WebSocket access.
- Reopen the modal, select QZ Direct Print, and click Refresh.
- Check the browser console for `QZ Tray connection failed`.

### Gainscha printer is not listed

- Confirm it appears in Windows **Printers & scanners**.
- Confirm its name contains `Gainscha`, `GE-2406`, or `GS-2406`.
- Restart the Windows Print Spooler and QZ Tray.
- Click Refresh again.

### Wrong printer receives the labels

- Select the correct queue in the barcode modal.
- The selection is saved per browser profile. Incognito/private mode should not be used.
- Rename similar Windows queues so operators can distinguish them.

### Labels feed too far or stop between stickers

- Run Gainscha media calibration.
- Verify Die-Cut stock and the `4.33in × 1.06in` profile.
- Verify the printer understands TSPL commands.
- Check the physical gap sensor and stock direction.

### Barcode does not scan

- Test Bar1 first, then Bar2 and Bar3.
- Reduce printer darkness if adjacent bars merge.
- Reduce print speed if bars are incomplete.
- Clean the thermal print head.
- Confirm the label was printed with QZ Direct Print and was not scaled by a browser dialog.
- Check the raw job uses a 2-dot narrow Code 128 module.

### Product name prints unsupported characters

The initial TSPL template uses the printer's built-in fonts for maximum sharpness. Printer code-page support varies. If product names require Myanmar or other unsupported Unicode characters, use Browser Print fallback until a monochrome bitmap/native Unicode template is added for that printer model.

## Security hardening before broad production rollout

The initial internal rollout uses QZ Tray's local trust prompt. For a warning-free controlled production rollout, configure QZ request signing with an organization certificate and restrict which site origin may issue print jobs. Do not place a private signing key in browser JavaScript; signing must happen on the Laravel server.

## Rollback

To return to the saved browser-only version:

```bash
git switch backup/barcode-browser-print-20260807
npm ci
npm run build
php artisan optimize:clear
```

Alternatively, operators can immediately select **Browser Print (Fallback)** without changing server code.
