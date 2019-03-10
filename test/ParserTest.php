<?php
declare(strict_types=1);

namespace Lcobucci\Jose\Parsing;

use PHPUnit\Framework\TestCase;
use function base64_decode;

final class ParserTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonEncode
     * @covers \Lcobucci\Jose\Parsing\Parser::verifyJsonError
     */
    public function jsonEncodeMustReturnAJSONString(): void
    {
        $encoder = new Parser();

        self::assertEquals('{"test":"test"}', $encoder->jsonEncode(['test' => 'test']));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonEncode
     * @covers \Lcobucci\Jose\Parsing\Parser::verifyJsonError
     */
    public function jsonEncodeShouldNotEscapeUnicode(): void
    {
        $encoder = new Parser();

        self::assertEquals('"汉语"', $encoder->jsonEncode('汉语'));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonEncode
     * @covers \Lcobucci\Jose\Parsing\Parser::verifyJsonError
     */
    public function jsonEncodeShouldNotEscapeSlashes(): void
    {
        $encoder = new Parser();

        self::assertEquals('"http://google.com"', $encoder->jsonEncode('http://google.com'));
    }

    /**
     * @test
     *
     * @expectedException \Lcobucci\Jose\Parsing\Exception
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonEncode
     * @covers \Lcobucci\Jose\Parsing\Parser::verifyJsonError
     */
    public function jsonEncodeMustRaiseExceptionWhenAnErrorHasOccurred(): void
    {
        $encoder = new Parser();
        $encoder->jsonEncode("\xB1\x31");
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonDecode
     * @covers \Lcobucci\Jose\Parsing\Parser::verifyJsonError
     */
    public function jsonDecodeMustReturnTheDecodedData(): void
    {
        $decoder = new Parser();

        self::assertEquals(
            ['test' => ['test' => []]],
            $decoder->jsonDecode('{"test":{"test":{}}}')
        );
    }

    /**
     * @test
     *
     * @expectedException \Lcobucci\Jose\Parsing\Exception
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonDecode
     * @covers \Lcobucci\Jose\Parsing\Parser::verifyJsonError
     */
    public function jsonDecodeMustRaiseExceptionWhenAnErrorHasOccurred(): void
    {
        $decoder = new Parser();
        $decoder->jsonDecode('{"test":\'test\'}');
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::base64UrlEncode
     */
    public function base64UrlEncodeMustReturnAnUrlSafeBase64(): void
    {
        $data = base64_decode('0MB2wKB+L3yvIdzeggmJ+5WOSLaRLTUPXbpzqUe0yuo=');

        $encoder = new Parser();
        self::assertEquals('0MB2wKB-L3yvIdzeggmJ-5WOSLaRLTUPXbpzqUe0yuo', $encoder->base64UrlEncode($data));
    }

    /**
     * @link https://tools.ietf.org/html/rfc7520#section-4
     *
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::base64UrlEncode
     */
    public function base64UrlEncodeMustEncodeBilboMessageProperly(): void
    {
        $message = 'It’s a dangerous business, Frodo, going out your door. You step '
                   . "onto the road, and if you don't keep your feet, there’s no knowing "
                   . 'where you might be swept off to.';

        $expected = 'SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IH'
                    . 'lvdXIgZG9vci4gWW91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBk'
                    . 'b24ndCBrZWVwIHlvdXIgZmVldCwgdGhlcmXigJlzIG5vIGtub3dpbmcgd2hlcm'
                    . 'UgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4';

        $encoder = new Parser();
        self::assertEquals($expected, $encoder->base64UrlEncode($message));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::base64UrlDecode
     */
    public function base64UrlDecodeMustReturnTheRightData(): void
    {
        $data = base64_decode('0MB2wKB+L3yvIdzeggmJ+5WOSLaRLTUPXbpzqUe0yuo=');

        $decoder = new Parser();
        self::assertEquals($data, $decoder->base64UrlDecode('0MB2wKB-L3yvIdzeggmJ-5WOSLaRLTUPXbpzqUe0yuo'));
    }

    /**
     * @link https://tools.ietf.org/html/rfc7520#section-4
     *
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::base64UrlDecode
     */
    public function base64UrlDecodeMustDecodeBilboMessageProperly(): void
    {
        $message = 'SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IH'
                   . 'lvdXIgZG9vci4gWW91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBk'
                   . 'b24ndCBrZWVwIHlvdXIgZmVldCwgdGhlcmXigJlzIG5vIGtub3dpbmcgd2hlcm'
                   . 'UgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4';

        $expected = 'It’s a dangerous business, Frodo, going out your door. You step '
                    . "onto the road, and if you don't keep your feet, there’s no knowing "
                    . 'where you might be swept off to.';

        $encoder = new Parser();
        self::assertEquals($expected, $encoder->base64UrlDecode($message));
    }
}
