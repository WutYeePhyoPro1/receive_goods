package com.kpay.receivedgoods.printagent

import android.app.Activity
import android.app.AlertDialog
import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent
import android.content.IntentFilter
import android.hardware.usb.UsbManager
import android.net.Uri
import android.os.Bundle
import android.os.Build
import android.view.KeyEvent
import android.view.Menu
import android.view.MenuItem
import android.webkit.WebChromeClient
import android.webkit.WebResourceRequest
import android.webkit.WebView
import android.webkit.WebViewClient
import android.widget.EditText
import android.widget.LinearLayout
import android.widget.Toast

class MainActivity : Activity() {
    companion object {
        private const val PREFS = "received_goods_print_agent"
        private const val SERVER_URL_KEY = "server_url"
        private const val DEFAULT_SERVER_URL = "http://192.168.2.24:7788"
        private const val MENU_SERVER = 1
        private const val MENU_RELOAD = 2
    }

    private lateinit var webView: WebView
    private val usbPermissionReceiver = object : BroadcastReceiver() {
        override fun onReceive(context: Context?, intent: Intent?) {
            if (intent?.action != UsbPrintBridge.ACTION_USB_PERMISSION) return
            val granted = intent.getBooleanExtra(UsbManager.EXTRA_PERMISSION_GRANTED, false)
            Toast.makeText(
                this@MainActivity,
                if (granted) "USB printer permission ရပါပြီ။" else "USB printer permission ငြင်းပယ်ထားသည်။",
                Toast.LENGTH_LONG,
            ).show()
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            registerReceiver(
                usbPermissionReceiver,
                IntentFilter(UsbPrintBridge.ACTION_USB_PERMISSION),
                RECEIVER_NOT_EXPORTED,
            )
        } else {
            @Suppress("DEPRECATION")
            registerReceiver(usbPermissionReceiver, IntentFilter(UsbPrintBridge.ACTION_USB_PERMISSION))
        }
        configureWebView()
        setContentView(webView)

        val preferences = getSharedPreferences(PREFS, MODE_PRIVATE)
        val savedUrl = preferences.getString(SERVER_URL_KEY, DEFAULT_SERVER_URL) ?: DEFAULT_SERVER_URL
        webView.loadUrl(savedUrl)
        if (!preferences.contains(SERVER_URL_KEY)) showServerDialog()
    }

    @Suppress("SetJavaScriptEnabled")
    private fun configureWebView() {
        webView = WebView(this).apply {
            settings.javaScriptEnabled = true
            settings.domStorageEnabled = true
            settings.allowFileAccess = false
            settings.allowContentAccess = false
            webChromeClient = WebChromeClient()
            webViewClient = object : WebViewClient() {
                override fun shouldOverrideUrlLoading(view: WebView?, request: WebResourceRequest?): Boolean {
                    val destination = request?.url ?: return true
                    if (isTrustedServer(destination)) return false
                    Toast.makeText(
                        this@MainActivity,
                        "Print Agent bridge ကို configured Received Goods server မှာပဲ သုံးနိုင်ပါတယ်။",
                        Toast.LENGTH_LONG,
                    ).show()
                    return true
                }
            }
            addJavascriptInterface(UsbPrintBridge(this@MainActivity), "AndroidPrintAgent")
            setOnLongClickListener {
                showServerDialog()
                true
            }
        }
    }

    private fun isTrustedServer(destination: Uri): Boolean {
        val configured = getSharedPreferences(PREFS, MODE_PRIVATE)
            .getString(SERVER_URL_KEY, DEFAULT_SERVER_URL) ?: DEFAULT_SERVER_URL
        val trusted = Uri.parse(configured)
        val destinationPort = if (destination.port == -1) defaultPort(destination.scheme) else destination.port
        val trustedPort = if (trusted.port == -1) defaultPort(trusted.scheme) else trusted.port
        return destination.scheme == trusted.scheme &&
            destination.host == trusted.host &&
            destinationPort == trustedPort
    }

    private fun defaultPort(scheme: String?): Int = if (scheme == "https") 443 else 80

    override fun onCreateOptionsMenu(menu: Menu): Boolean {
        menu.add(0, MENU_SERVER, 0, "Server URL")
        menu.add(0, MENU_RELOAD, 1, "Reload")
        return true
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean = when (item.itemId) {
        MENU_SERVER -> {
            showServerDialog()
            true
        }
        MENU_RELOAD -> {
            webView.reload()
            true
        }
        else -> super.onOptionsItemSelected(item)
    }

    private fun showServerDialog() {
        val preferences = getSharedPreferences(PREFS, MODE_PRIVATE)
        val input = EditText(this).apply {
            setText(preferences.getString(SERVER_URL_KEY, DEFAULT_SERVER_URL))
            setSingleLine(true)
            selectAll()
        }
        val container = LinearLayout(this).apply {
            setPadding(32, 8, 32, 0)
            addView(input)
        }
        AlertDialog.Builder(this)
            .setTitle("Received Goods Server URL")
            .setView(container)
            .setPositiveButton("Save") { _, _ ->
                val url = input.text.toString().trim().trimEnd('/')
                if (url.startsWith("http://") || url.startsWith("https://")) {
                    preferences.edit().putString(SERVER_URL_KEY, url).apply()
                    webView.loadUrl(url)
                } else {
                    Toast.makeText(this, "http:// သို့မဟုတ် https:// URL ထည့်ပါ။", Toast.LENGTH_LONG).show()
                }
            }
            .setNegativeButton("Cancel", null)
            .show()
    }

    override fun onKeyDown(keyCode: Int, event: KeyEvent?): Boolean {
        if (keyCode == KeyEvent.KEYCODE_BACK && webView.canGoBack()) {
            webView.goBack()
            return true
        }
        return super.onKeyDown(keyCode, event)
    }

    override fun onDestroy() {
        unregisterReceiver(usbPermissionReceiver)
        webView.removeJavascriptInterface("AndroidPrintAgent")
        webView.destroy()
        super.onDestroy()
    }
}
