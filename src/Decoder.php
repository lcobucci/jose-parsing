<?php
/**
 * This file is part of Lcobucci\Jose\Parsing, a simple library to encode and decode JOSE objects
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

declare(strict_types=1);

namespace Lcobucci\Jose\Parsing;

/**
 * An utilitarian class that decodes data according with JOSE specifications
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 1.0.0
 */
interface Decoder
{
    /**
     * Decodes from JSON, validating the errors
     *
     * @param string $json
     * @return mixed
     *
     * @throws Exception When something goes wrong while decoding
     */
    public function jsonDecode(string $json);

    /**
     * Decodes from Base64URL
     *
     * @param string $data
     *
     * @return string
     *
     * @link http://tools.ietf.org/html/rfc4648#section-5
     */
    public function base64UrlDecode(string $data): string;
}
