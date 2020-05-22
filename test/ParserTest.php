<?php
declare(strict_types=1);

namespace Lcobucci\Jose\Parsing;

use PHPUnit\Framework\TestCase;
use function assert;
use function base64_decode;
use function is_string;

final class ParserTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonEncode
     */
    public function jsonEncodeMustReturnAJSONString(): void
    {
        $encoder = new Parser();

        self::assertSame('{"test":"test"}', $encoder->jsonEncode(['test' => 'test']));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonEncode
     */
    public function jsonEncodeShouldNotEscapeUnicode(): void
    {
        $encoder = new Parser();

        self::assertSame('"汉语"', $encoder->jsonEncode('汉语'));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonEncode
     */
    public function jsonEncodeShouldNotEscapeSlashes(): void
    {
        $encoder = new Parser();

        self::assertSame('"http://google.com"', $encoder->jsonEncode('http://google.com'));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonEncode
     */
    public function jsonEncodeMustRaiseExceptionWhenAnErrorHasOccurred(): void
    {
        $encoder = new Parser();

        $this->expectException(Exception::class);
        $this->expectExceptionCode(0);
        $encoder->jsonEncode("\xB1\x31");
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonDecode
     */
    public function jsonDecodeMustReturnTheDecodedData(): void
    {
        $decoder = new Parser();

        self::assertSame(
            ['test' => ['test' => []]],
            $decoder->jsonDecode('{"test":{"test":{}}}')
        );
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::jsonDecode
     */
    public function jsonDecodeMustRaiseExceptionWhenAnErrorHasOccurred(): void
    {
        $decoder = new Parser();

        $this->expectException(Exception::class);
        $this->expectExceptionCode(0);
        $decoder->jsonDecode('{"test":\'test\'}');
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::base64UrlEncode
     */
    public function base64UrlEncodeMustReturnAnUrlSafeBase64(): void
    {
        $data = base64_decode('0MB2wKB+L3yvIdzeggmJ+5WOSLaRLTUPXbpzqUe0yuo=', true);
        assert(is_string($data));

        $encoder = new Parser();
        self::assertSame('0MB2wKB-L3yvIdzeggmJ-5WOSLaRLTUPXbpzqUe0yuo', $encoder->base64UrlEncode($data));
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
        self::assertSame($expected, $encoder->base64UrlEncode($message));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::base64UrlDecode
     */
    public function base64UrlDecodeMustRaiseExceptionWhenInvalidBase64CharsAreUsed(): void
    {
        $decoder = new Parser();

        $this->expectException(Exception::class);
        $this->expectExceptionCode(0);
        $decoder->base64UrlDecode('áááááá');
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::base64UrlDecode
     */
    public function base64UrlDecodeMustReturnTheRightData(): void
    {
        $data = base64_decode('0MB2wKB+L3yvIdzeggmJ+5WOSLaRLTUPXbpzqUe0yuo=', true);

        $decoder = new Parser();
        self::assertSame($data, $decoder->base64UrlDecode('0MB2wKB-L3yvIdzeggmJ-5WOSLaRLTUPXbpzqUe0yuo'));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::base64UrlDecode
     */
    public function base64UrlDecodeMustAddPaddingToDecodeThins(): void
    {
        $decoder = new Parser();
        self::assertSame('I', $decoder->base64UrlDecode('SQ'));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Jose\Parsing\Parser::base64UrlDecode
     */
    public function base64UrlDecodeShouldAlsoNotAddPaddingWhenItIsNotNeeded(): void
    {
        $decoder = new Parser();

        self::assertSame('any carnal pleasur', $decoder->base64UrlDecode('YW55IGNhcm5hbCBwbGVhc3Vy'));
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
        self::assertSame($expected, $encoder->base64UrlDecode($message));
    }
}
