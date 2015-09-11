<?php
/**
 * This file is part of Lcobucci\Jose\Parsing, a simple library to encode and decode JOSE objects
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Lcobucci\Jose\Parsing;

/**
 * An utilitarion class that encodes data according with JOSE specifications
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 1.0.0
 */
class Encoder
{
    /**
     * Encodes to JSON, validating the errors
     *
     * @param mixed $data
     *
     * @return string
     *
     * @throws Exception When something goes wrong while encoding
     */
    public function jsonEncode($data)
    {
        $json = json_encode($data);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new Exception('Error while encoding to JSON: ' . json_last_error_msg());
        }

        return $json;
    }

    /**
     * Encodes to base64url
     *
     * @param string $data
     *
     * @return string
     *
     * @link http://tools.ietf.org/html/rfc4648#section-5
     */
    public function base64UrlEncode($data)
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }
}
