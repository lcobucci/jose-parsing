<?php
declare(strict_types=1);

namespace Lcobucci\Jose\Parsing;

/**
 * A utilitarian class that decodes data according with JOSE specifications
 */
interface Decoder
{
    /**
     * Decodes from JSON, validating the errors
     *
     * @return mixed
     *
     * @throws Exception When something goes wrong while decoding.
     */
    public function jsonDecode(string $json);

    /**
     * Decodes from Base64URL
     *
     * @link http://tools.ietf.org/html/rfc4648#section-5
     */
    public function base64UrlDecode(string $data): string;
}
