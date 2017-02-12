<?php
/**
 * This file is part of Lcobucci\Jose\Parsing, a simple library to encode and decode JOSE objects
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

declare(strict_types=1);

namespace Lcobucci\Jose\Parsing;

/**
 * An utilitarian class that encodes and decodes data according with JOSE specifications
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 2.1.0
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
     * @param string $message
     *
     * @throws Exception
     */
    private function verifyJsonError(string $message): void
    {
        if (json_last_error() != JSON_ERROR_NONE) {
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
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }
}
