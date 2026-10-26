<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Compression is needed because the web server cannot be changed: it sends
 * large JSON payloads uncompressed, and a 1.4 MB datatable response takes about
 * 20 s to download on the office link even though the server answers in 1.6 s.
 *
 * The middleware must compress once, only when the client asks for gzip, only
 * for text-shaped bodies, and never when a Content-Encoding is already set
 * (a web server or proxy may have done it first).
 */
class CompressResponseTest extends TestCase
{
    private function probe(string $uri, int $bytes, string $type = 'json', array $headers = []): void
    {
        Route::get($uri, function () use ($bytes, $type, $headers) {
            $payload = str_repeat('OPTV2', (int) ceil($bytes / 5));

            if ($type === 'json') {
                return response()->json(['blob' => $payload], 200, $headers);
            }

            return response($payload, 200, array_merge(['Content-Type' => $type], $headers));
        });
    }

    public function test_large_json_is_compressed_when_the_client_accepts_gzip(): void
    {
        $this->probe('/__gzip_json', 20000);

        $response = $this->get('/__gzip_json', ['Accept-Encoding' => 'gzip, deflate, br']);

        $response->assertStatus(200);
        $response->assertHeader('Content-Encoding', 'gzip');
        $response->assertHeader('Vary', 'Accept-Encoding');

        $decoded = gzdecode($response->getContent());
        $this->assertIsString($decoded);
        $this->assertStringContainsString('OPTV2', $decoded);
        $this->assertLessThan(strlen($decoded), strlen($response->getContent()), 'compressed body must be smaller');
    }

    public function test_large_html_is_compressed(): void
    {
        $this->probe('/__gzip_html', 20000, 'text/html; charset=UTF-8');

        $this->get('/__gzip_html', ['Accept-Encoding' => 'gzip'])
            ->assertHeader('Content-Encoding', 'gzip');
    }

    public function test_nothing_happens_when_the_client_does_not_accept_gzip(): void
    {
        $this->probe('/__gzip_none', 20000);

        $response = $this->get('/__gzip_none');

        $response->assertStatus(200);
        $response->assertHeaderMissing('Content-Encoding');
        $this->assertStringContainsString('OPTV2', $response->getContent());
    }

    public function test_an_already_encoded_response_is_not_encoded_again(): void
    {
        $this->probe('/__gzip_twice', 20000, 'json', ['Content-Encoding' => 'gzip']);

        $response = $this->get('/__gzip_twice', ['Accept-Encoding' => 'gzip']);

        $this->assertSame(
            1,
            substr_count(strtolower((string) $response->headers->get('Content-Encoding')), 'gzip'),
            'must not add a second gzip layer'
        );
    }

    public function test_small_payloads_are_left_alone(): void
    {
        $this->probe('/__gzip_small', 200);

        $this->get('/__gzip_small', ['Accept-Encoding' => 'gzip'])
            ->assertHeaderMissing('Content-Encoding');
    }

    public function test_binary_payloads_are_left_alone(): void
    {
        $this->probe('/__gzip_pdf', 20000, 'application/pdf');

        $this->get('/__gzip_pdf', ['Accept-Encoding' => 'gzip'])
            ->assertHeaderMissing('Content-Encoding');
    }
}
