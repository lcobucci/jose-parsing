<?php
/**
 * This file is part of Lcobucci\Jose\Parsing, a simple library to encode and decode JOSE objects
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

declare(strict_types=1);

namespace Lcobucci\Jose\Parsing;

/**
 * An utilitarian class that encodes data according with JOSE specifications
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 1.0.0
 */
interface Encoder
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
    public function jsonEncode($data): string;

    /**
     * Encodes to base64url
     *
     * @param string $data
     *
     * @return string
     *
     * @link http://tools.ietf.org/html/rfc4648#section-5
     */
    public function base64UrlEncode(string $data): string;
}
