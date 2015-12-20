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
 * @since 2.1.0
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Parser::jsonEncode
     */
    public function jsonEncodeMustReturnAJSONString()
    {
        $encoder = new Parser();

        $this->assertEquals('{"test":"test"}', $encoder->jsonEncode(['test' => 'test']));
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Parser::jsonEncode
     *
     * @expectedException \Lcobucci\Jose\Parsing\Exception
     */
    public function jsonEncodeMustRaiseExceptionWhenAnErrorHasOccured()
    {
        $encoder = new Parser();
        $encoder->jsonEncode("\xB1\x31");
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Parser::jsonDecode
     */
    public function jsonDecodeMustReturnTheDecodedData()
    {
        $decoder = new Parser();

        $this->assertEquals(
            ['test' => ['test' => []]],
            $decoder->jsonDecode('{"test":{"test":{}}}')
        );
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Parser::jsonDecode
     *
     * @expectedException \Lcobucci\Jose\Parsing\Exception
     */
    public function jsonDecodeMustRaiseExceptionWhenAnErrorHasOccured()
    {
        $decoder = new Parser();
        $decoder->jsonDecode('{"test":\'test\'}');
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Parser::base64UrlEncode
     */
    public function base64UrlEncodeMustReturnAnUrlSafeBase64()
    {
        $data = base64_decode('0MB2wKB+L3yvIdzeggmJ+5WOSLaRLTUPXbpzqUe0yuo=');

        $encoder = new Parser();
        $this->assertEquals('0MB2wKB-L3yvIdzeggmJ-5WOSLaRLTUPXbpzqUe0yuo', $encoder->base64UrlEncode($data));
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Parser::base64UrlEncode
     *
     * @link https://tools.ietf.org/html/rfc7520#section-4
     */
    public function base64UrlEncodeMustEncodeBilboMessageProperly()
    {
        $message = "It’s a dangerous business, Frodo, going out your door. You step "
                   . "onto the road, and if you don't keep your feet, there’s no knowing "
                   . "where you might be swept off to.";

        $expected = 'SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IH'
                    . 'lvdXIgZG9vci4gWW91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBk'
                    . 'b24ndCBrZWVwIHlvdXIgZmVldCwgdGhlcmXigJlzIG5vIGtub3dpbmcgd2hlcm'
                    . 'UgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4';

        $encoder = new Parser();
        $this->assertEquals($expected, $encoder->base64UrlEncode($message));
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Parser::base64UrlDecode
     */
    public function base64UrlDecodeMustReturnTheRightData()
    {
        $data = base64_decode('0MB2wKB+L3yvIdzeggmJ+5WOSLaRLTUPXbpzqUe0yuo=');

        $decoder = new Parser();
        $this->assertEquals($data, $decoder->base64UrlDecode('0MB2wKB-L3yvIdzeggmJ-5WOSLaRLTUPXbpzqUe0yuo'));
    }

    /**
     * @test
     *
     * @covers Lcobucci\Jose\Parsing\Parser::base64UrlDecode
     *
     * @link https://tools.ietf.org/html/rfc7520#section-4
     */
    public function base64UrlDecodeMustDecodeBilboMessageProperly()
    {
        $message = 'SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IH'
                   . 'lvdXIgZG9vci4gWW91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBk'
                   . 'b24ndCBrZWVwIHlvdXIgZmVldCwgdGhlcmXigJlzIG5vIGtub3dpbmcgd2hlcm'
                   . 'UgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4';

        $expected = "It’s a dangerous business, Frodo, going out your door. You step "
                    . "onto the road, and if you don't keep your feet, there’s no knowing "
                    . "where you might be swept off to.";


        $encoder = new Parser();
        $this->assertEquals($expected, $encoder->base64UrlDecode($message));
    }
}
