<?php
declare(strict_types=1);

namespace Lcobucci\Jose\Parsing;

use JsonException;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use function base64_decode;
use function base64_encode;
use function is_string;
use function json_decode;
use function json_encode;
use function str_repeat;
use function str_replace;
use function strlen;
use function strtr;

/**
 * A utilitarian class that encodes and decodes data according with JOSE specifications
 */
final class Parser implements Encoder, Decoder
{
    private const JSON_DEFAULT_DEPTH    = 512;
    private const BASE64_PADDING_LENGTH = 4;

    /**
     * {@inheritdoc}
     */
    public function jsonEncode($data): string
    {
        try {
            return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new Exception('Error while encoding to JSON', 0, $exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function jsonDecode(string $json)
    {
        try {
            return json_decode($json, true, self::JSON_DEFAULT_DEPTH, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new Exception('Error while decoding to JSON', 0, $exception);
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
        $remainder = strlen($data) % self::BASE64_PADDING_LENGTH;

        if ($remainder !== 0) {
            $data .= str_repeat('=', self::BASE64_PADDING_LENGTH - $remainder);
        }

        $decodedContent = base64_decode(strtr($data, '-_', '+/'), true);

        if (! is_string($decodedContent)) {
            throw new Exception('Error while decoding from Base64: invalid characters used');
        }

        return $decodedContent;
    }
}
