<?php
/**
 * This file is part of Lcobucci\Jose\Parsing, a simple library to encode and decode JOSE objects
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Lcobucci\Jose\Parsing;

/**
 * An utilitarion class that decodes data according with JOSE specifications
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 1.0.0
 */
class Decoder
{
    /**
     * Decodes from JSON, validating the errors
     *
     * @param string $json
     * @return mixed
     *
     * @throws Exception When something goes wrong while decoding
     */
    public function jsonDecode($json)
    {
        $data = json_decode($json);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new Exception('Error while decoding to JSON: ' . json_last_error_msg());
        }

        return $data;
    }

    /**
     * Decodes from Base64URL
     *
     * @param string $data
     *
     * @return string
     *
     * @link http://tools.ietf.org/html/rfc4648#section-5
     */
    public function base64UrlDecode($data)
    {
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }
}
