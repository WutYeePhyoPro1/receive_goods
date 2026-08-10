<?php

namespace App\Http\Controllers;

use App\Services\QzMessageSigner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class QzSecurityController extends Controller
{
    public function __construct(private readonly QzMessageSigner $signer)
    {
    }

    public function certificate(): Response|JsonResponse
    {
        try {
            return response($this->signer->certificate(), 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
                'Cache-Control' => 'no-store, private',
            ]);
        } catch (RuntimeException $exception) {
            Log::error('QZ certificate could not be loaded.', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json(['message' => 'QZ certificate is unavailable.'], 500);
        }
    }

    public function sign(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'request' => ['required', 'string', 'max:1048576'],
        ]);

        try {
            return response()->json([
                'signature' => $this->signer->sign($validated['request']),
            ]);
        } catch (RuntimeException $exception) {
            Log::error('QZ request signing failed.', [
                'message' => $exception->getMessage(),
                'user_id' => $request->user()?->getAuthIdentifier(),
            ]);

            return response()->json(['message' => 'QZ request could not be signed.'], 500);
        }
    }
}
