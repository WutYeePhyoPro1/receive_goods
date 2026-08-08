# QZ Tray remote Windows print server

This mode keeps the Gainscha printer connected to Windows by USB. A tablet on the same LAN opens
Received Goods and connects to QZ Tray on the Windows IP address. Android's **Add printer by IP** screen
is not used.

## Architecture

`Tablet browser -> ws://WINDOWS_IP:8182 -> Windows QZ Tray -> Gainscha USB printer`

Browser Print and local QZ Direct Print remain available as fallbacks. Leave the Windows IP field blank
to use QZ Tray on the same device as the browser.

## Windows setup

1. Install the Gainscha driver, connect the printer by USB, and confirm a Windows test page works.
2. Install and start QZ Tray 2.2.6 or a compatible supported version.
3. Give Windows a reserved/static LAN address, for example `192.168.2.50`. Run `ipconfig` to confirm it.
4. In QZ Tray choose **Advanced > Diagnostic > Browse App Folder**, open `qz-tray.properties`, and ensure:

   ```properties
   security.wss.host=0.0.0.0
   ```

5. Restart QZ Tray after changing the property.
6. In an Administrator PowerShell, allow only the private local subnet to reach the insecure QZ port:

   ```powershell
   New-NetFirewallRule -DisplayName "QZ Tray LAN 8182" `
     -Direction Inbound -Protocol TCP -LocalPort 8182 `
     -Action Allow -Profile Private -RemoteAddress LocalSubnet
   ```

7. Verify QZ is listening:

   ```cmd
   netstat -ano | findstr :8182
   ```

Do not expose port 8182 to the public internet. Windows and the tablet must be on the same trusted LAN.

## Tablet setup

1. Connect the tablet to the same Wi-Fi/LAN as Windows.
2. In Chrome open `http://WINDOWS_IP:8182/`. A QZ status/about page confirms network reachability.
3. Chrome may ask for Local Network Access; allow it for the Received Goods site.
4. If Android Chrome blocks the insecure WebSocket, open
   `chrome://flags/#unsafely-treat-insecure-origin-as-secure`, enable it, and add
   `ws://WINDOWS_IP:8182`, then restart Chrome. This follows QZ's Android print-server guidance.
5. Open Received Goods, choose **QZ Direct Print**, enter only the Windows IP (no protocol or port),
   press **Connect**, select the Gainscha printer, and run **Test**.

## Tracing

1. `http://WINDOWS_IP:8182/` does not open: IP, Wi-Fi isolation, QZ binding, or firewall issue.
2. Status page opens but Received Goods cannot connect: browser Local Network Access/mixed-content rule.
3. Connected but no printer listed: Windows driver/account cannot see the Gainscha printer.
4. Test works but normal print fails: inspect the browser console and QZ Tray logs.
5. QZ logs are available from **Advanced > Diagnostic > Browse User Folder**.

For production HTTPS, use QZ's certificate-based secure print-server setup rather than leaving a public
insecure WebSocket exposed.
