# Received Goods Android USB Print Agent

This Android app opens the Received Goods web application in a secured WebView and exposes a native
`AndroidPrintAgent` JavaScript bridge only to the configured server. The web app keeps generating the
same scan-safe TSPL bytes used by QZ Tray; the agent sends those bytes to a selected USB bulk OUT
endpoint through Android USB Host APIs.

## Requirements

- Android tablet with USB Host/OTG support (Android 8 or newer)
- USB-C OTG adapter; use a powered USB hub if the tablet cannot keep the printer connected
- Gainscha printer that exposes a USB bulk OUT endpoint
- Android Studio with JDK 17 and Android SDK 35
- Tablet and Received Goods server on the same network

## Build and install

1. Open `android-print-agent` in Android Studio.
2. Let Gradle sync and install Android SDK 35 if prompted.
3. Select **Build > Build APK(s)** for a test APK.
4. Enable installation from Android Studio/unknown sources on the tablet and install the APK.
5. Connect the powered-on printer through USB OTG before opening the app.
6. On first launch, enter the Received Goods base URL, for example `http://192.168.2.24:7788`.
   Long-press anywhere in the app to change this URL later.

## First print

1. Sign in to Received Goods inside the app.
2. Open the barcode print modal.
3. Select **Android USB Direct Print**.
4. Press **Detect**, select the Gainscha USB device, and allow Android's USB permission dialog.
5. Press **Test**. If successful, print Bar1, Bar2, and Bar3 from the normal Print button.

## Troubleshooting

- **No USB printer found:** confirm OTG support, reconnect the cable, power-cycle the printer, and try
  a powered USB hub. The agent lists devices only when they expose a bulk OUT endpoint.
- **Permission required:** select the printer, tap Allow in Android's system dialog, then press Test again.
- **Transfer stopped/open failed:** reconnect USB and make sure no Android print-service app has claimed
  the printer. Capture Android Studio Logcat while reproducing the issue.
- **Prints random text or nothing:** the USB transport works but the printer may not be in TSPL mode or may
  use a proprietary Android protocol. Obtain the Gainscha Android SDK for that exact model.
- **Website does not load:** long-press, verify the server URL, then confirm the tablet can open that URL on
  the same Wi-Fi network.

## Security

The JavaScript USB bridge accepts calls only while navigating within the configured scheme, host, and
port. Use HTTPS for production when available. Do not configure an untrusted public website as the
server URL.
