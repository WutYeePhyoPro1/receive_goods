<?php

namespace Tests\Unit;

use App\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class BarcodeTrimStringsTest extends TestCase
{
    public function test_scan_whitespace_is_preserved_for_form_and_json_requests(): void
    {
        $middleware = new TrimStrings();
        foreach ([" 12345", "12345 ", " S12345 ", "M12345\t", "L12345\n", "\u{200B}12345"] as $barcode) {
            $requests = [
                Request::create('/barcode_scan', 'POST', ['data' => $barcode, 'id' => ' 1 ']),
                Request::create('/barcode_scan', 'POST', [], [], [],
                    ['CONTENT_TYPE' => 'application/json'],
                    json_encode(['data' => $barcode, 'id' => ' 1 '])),
            ];
            foreach ($requests as $request) {
                $middleware->handle($request, function ($request) use ($barcode) {
                    $this->assertSame($barcode, $request->input('data'));
                    $this->assertSame('1', $request->input('id'));
                });
            }
        }

        // Reusing the middleware must not disable trimming on other endpoints.
        $middleware->handle(Request::create('/search_doc', 'POST', ['data' => ' 12345 ']), function ($request) {
            $this->assertSame('12345', $request->input('data'));
        });
    }
}
