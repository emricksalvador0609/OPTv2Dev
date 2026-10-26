<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Gzips text responses in the application.
 *
 * The live web server is not under our control and sends the stock allocation
 * datatable uncompressed: 1.4 MB of JSON takes about 20 s to download on the
 * office link, while the server answers in 1.6 s. Enabling gzip here divides
 * that by roughly ten without touching the web server.
 *
 * Safety rules:
 *  - only when the client sent Accept-Encoding: gzip
 *  - only for text-shaped bodies above MIN_BYTES
 *  - never when a Content-Encoding is already present, so a web server or proxy
 *    that compresses first cannot be compressed twice
 *  - never for streamed or file responses
 */
class CompressResponse
{
    private const MIN_BYTES = 1024;

    /**
     * Content types that are safe to compress. Binary payloads (PDF, images,
     * exports) are left untouched.
     */
    private const COMPRESSIBLE = [
        'application/json',
        'application/javascript',
        'application/xml',
        'text/html',
        'text/plain',
        'text/css',
        'text/csv',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!$this->shouldCompress($request, $response)) {
            return $response;
        }

        $content = $response->getContent();
        $compressed = gzencode($content, 6);

        if ($compressed === false || strlen($compressed) >= strlen($content)) {
            return $response;
        }

        $response->setContent($compressed);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Vary', 'Accept-Encoding', false);

        // The decoded length no longer matches, and a stale length truncates
        // the body in the browser.
        $response->headers->remove('Content-Length');

        return $response;
    }

    private function shouldCompress(Request $request, Response $response): bool
    {
        if ($request->isMethod('HEAD')) {
            return false;
        }

        if ($response instanceof StreamedResponse || $response instanceof BinaryFileResponse) {
            return false;
        }

        if ($response->headers->has('Content-Encoding')) {
            return false;
        }

        if (!in_array('gzip', $request->getEncodings(), true)) {
            return false;
        }

        $contentType = strtolower((string) $response->headers->get('Content-Type'));

        if (!$this->isCompressibleType($contentType)) {
            return false;
        }

        return strlen((string) $response->getContent()) >= self::MIN_BYTES;
    }

    private function isCompressibleType(string $contentType): bool
    {
        if ($contentType === '') {
            return false;
        }

        foreach (self::COMPRESSIBLE as $type) {
            if (str_starts_with($contentType, $type)) {
                return true;
            }
        }

        return false;
    }
}
