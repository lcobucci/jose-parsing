<?php
declare(strict_types=1);

namespace Lcobucci\Jose\Parsing;

use const JSON_ERROR_NONE;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use function base64_decode;
use function base64_encode;
use function json_decode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;
use function sprintf;
use function str_repeat;
use function str_replace;
use function strlen;
use function strtr;

/**
 * A utilitarian class that encodes and decodes data according with JOSE specifications
 */
final class Parser implements Encoder, Decoder
{
    /**
     * {@inheritdoc}
     */
    public function jsonEncode($data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->verifyJsonError('Error while encoding to JSON');

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonDecode(string $json)
    {
        $data = json_decode($json, true);
        $this->verifyJsonError('Error while decoding from JSON');

        return $data;
    }

    /**
     * Throws a parsing exception when an error happened while encoding or decoding
     *
     * @throws Exception
     */
    private function verifyJsonError(string $message): void
    {
        $error = json_last_error();

        if ($error !== JSON_ERROR_NONE) {
            throw new Exception(sprintf('%s: %s', $message, json_last_error_msg()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function base64UrlEncode(string $data): string
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }

    /**
     * {@inheritdoc}
     */
    public function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;

        if ($remainder !== 0) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }
}
