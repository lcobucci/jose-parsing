<?php
/**
 * This file is part of Lcobucci\Jose\Parsing, a simple library to encode and decode JOSE objects
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

declare(strict_types=1);

namespace Lcobucci\Jose\Parsing;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 1.0.0
 */
class DecoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Decoder::jsonDecode
     */
    public function jsonDecodeMustReturnTheDecodedData()
    {
        $decoder = new Decoder();

        $this->assertEquals(
            (object) ['test' => 'test'],
            $decoder->jsonDecode('{"test":"test"}')
        );
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Decoder::jsonDecode
     *
     * @expectedException \Lcobucci\Jose\Parsing\Exception
     */
    public function jsonDecodeMustRaiseExceptionWhenAnErrorHasOccured()
    {
        $decoder = new Decoder();
        $decoder->jsonDecode('{"test":\'test\'}');
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Decoder::base64UrlDecode
     */
    public function base64UrlDecodeMustReturnTheRightData()
    {
        $data = base64_decode('0MB2wKB+L3yvIdzeggmJ+5WOSLaRLTUPXbpzqUe0yuo=');

        $decoder = new Decoder();
        $this->assertEquals($data, $decoder->base64UrlDecode('0MB2wKB-L3yvIdzeggmJ-5WOSLaRLTUPXbpzqUe0yuo'));
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Decoder::base64UrlDecode
     *
     * @link https://tools.ietf.org/html/rfc7520#section-4
     */
    public function base64UrlEncodeMustEncodeBilboMessageProperly()
    {
        $message = 'SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IH'
                   . 'lvdXIgZG9vci4gWW91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBk'
                   . 'b24ndCBrZWVwIHlvdXIgZmVldCwgdGhlcmXigJlzIG5vIGtub3dpbmcgd2hlcm'
                   . 'UgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4';

        $expected = "It’s a dangerous business, Frodo, going out your door. You step "
                    . "onto the road, and if you don't keep your feet, there’s no knowing "
                    . "where you might be swept off to.";


        $encoder = new Decoder();
        $this->assertEquals($expected, $encoder->base64UrlDecode($message));
    }
}
