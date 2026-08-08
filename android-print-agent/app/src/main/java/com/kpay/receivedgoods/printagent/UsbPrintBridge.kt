package com.kpay.receivedgoods.printagent

import android.app.PendingIntent
import android.content.Context
import android.content.Intent
import android.hardware.usb.UsbConstants
import android.hardware.usb.UsbDevice
import android.hardware.usb.UsbDeviceConnection
import android.hardware.usb.UsbEndpoint
import android.hardware.usb.UsbInterface
import android.hardware.usb.UsbManager
import android.util.Base64
import android.webkit.JavascriptInterface
import org.json.JSONArray
import org.json.JSONObject

class UsbPrintBridge(private val context: Context) {
    companion object {
        const val ACTION_USB_PERMISSION = "com.kpay.receivedgoods.USB_PERMISSION"
        private const val PREFS = "received_goods_print_agent"
        private const val DEVICE_KEY = "usb_device_name"
        private const val TRANSFER_TIMEOUT_MS = 15_000
        private const val CHUNK_SIZE = 16 * 1024
    }

    private val usbManager = context.getSystemService(Context.USB_SERVICE) as UsbManager
    private val preferences = context.getSharedPreferences(PREFS, Context.MODE_PRIVATE)

    @JavascriptInterface
    fun isAvailable(): Boolean = true

    @JavascriptInterface
    fun listPrinters(): String {
        val devices = JSONArray()
        usbManager.deviceList.values
            .filter { findBulkOut(it) != null }
            .forEach { device ->
                devices.put(JSONObject().apply {
                    put("name", device.deviceName)
                    put("label", device.productName ?: "USB ${device.vendorId}:${device.productId}")
                    put("vendorId", device.vendorId)
                    put("productId", device.productId)
                    put("hasPermission", usbManager.hasPermission(device))
                    put("selected", device.deviceName == preferences.getString(DEVICE_KEY, null))
                })
            }
        return devices.toString()
    }

    @JavascriptInterface
    fun selectPrinter(deviceName: String): String {
        val device = usbManager.deviceList[deviceName]
            ?: return result(false, "USB printer မတွေ့ပါ။")
        if (findBulkOut(device) == null) return result(false, "USB bulk output မတွေ့ပါ။")

        preferences.edit().putString(DEVICE_KEY, deviceName).apply()
        if (!usbManager.hasPermission(device)) {
            val permissionIntent = PendingIntent.getBroadcast(
                context,
                0,
                Intent(ACTION_USB_PERMISSION).setPackage(context.packageName),
                PendingIntent.FLAG_UPDATE_CURRENT or PendingIntent.FLAG_IMMUTABLE,
            )
            usbManager.requestPermission(device, permissionIntent)
            return result(false, "USB permission dialog ကို Allow လုပ်ပြီး Test ပြန်နှိပ်ပါ။", "permission_required")
        }
        return result(true, "USB printer ready ဖြစ်ပါပြီ။")
    }

    @JavascriptInterface
    fun printBase64(base64Data: String): String {
        val selectedName = preferences.getString(DEVICE_KEY, null)
            ?: return result(false, "USB printer အရင်ရွေးပါ။", "printer_not_selected")
        val device = usbManager.deviceList[selectedName]
            ?: return result(false, "ရွေးထားသော USB printer ပြုတ်နေပါသည်။", "printer_disconnected")
        if (!usbManager.hasPermission(device)) {
            selectPrinter(selectedName)
            return result(false, "USB permission လိုအပ်ပါသည်။ Allow လုပ်ပြီး Print ပြန်နှိပ်ပါ။", "permission_required")
        }

        val target = findBulkOut(device)
            ?: return result(false, "Printer USB output endpoint မတွေ့ပါ။", "endpoint_not_found")
        val bytes = try {
            Base64.decode(base64Data, Base64.DEFAULT)
        } catch (_: IllegalArgumentException) {
            return result(false, "Print data ပျက်နေပါသည်။", "invalid_data")
        }
        val connection = usbManager.openDevice(device)
            ?: return result(false, "USB printer ကိုဖွင့်မရပါ။", "open_failed")

        return try {
            if (!connection.claimInterface(target.first, true)) {
                result(false, "Printer USB interface ကိုယူမရပါ။", "claim_failed")
            } else {
                transferAll(connection, target.second, bytes)
                result(true, "${bytes.size} bytes printer ဆီပို့ပြီးပါပြီ။")
            }
        } catch (error: Exception) {
            result(false, error.message ?: "USB print မအောင်မြင်ပါ။", "transfer_failed")
        } finally {
            connection.releaseInterface(target.first)
            connection.close()
        }
    }

    private fun transferAll(connection: UsbDeviceConnection, endpoint: UsbEndpoint, bytes: ByteArray) {
        var offset = 0
        while (offset < bytes.size) {
            val length = minOf(CHUNK_SIZE, bytes.size - offset)
            val chunk = bytes.copyOfRange(offset, offset + length)
            val written = connection.bulkTransfer(endpoint, chunk, chunk.size, TRANSFER_TIMEOUT_MS)
            if (written <= 0) throw IllegalStateException("USB transfer $offset byte နေရာတွင် ရပ်သွားပါသည်။")
            offset += written
        }
    }

    private fun findBulkOut(device: UsbDevice): Pair<UsbInterface, UsbEndpoint>? {
        for (interfaceIndex in 0 until device.interfaceCount) {
            val usbInterface = device.getInterface(interfaceIndex)
            for (endpointIndex in 0 until usbInterface.endpointCount) {
                val endpoint = usbInterface.getEndpoint(endpointIndex)
                if (endpoint.type == UsbConstants.USB_ENDPOINT_XFER_BULK &&
                    endpoint.direction == UsbConstants.USB_DIR_OUT
                ) {
                    return usbInterface to endpoint
                }
            }
        }
        return null
    }

    private fun result(success: Boolean, message: String, code: String = ""): String =
        JSONObject().apply {
            put("success", success)
            put("message", message)
            if (code.isNotEmpty()) put("code", code)
        }.toString()
}
