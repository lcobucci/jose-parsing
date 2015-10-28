# Javascript Object Signing and Encoding Parsing
[![Gitter](https://img.shields.io/badge/GITTER-JOIN%20CHAT%20%E2%86%92-brightgreen.svg?style=flat-square)](https://gitter.im/lcobucci/jose-parsing?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Total Downloads](https://img.shields.io/packagist/dt/lcobucci/jose-parsing.svg?style=flat-square)](https://packagist.org/packages/lcobucci/jose-parsing)
[![Latest Stable Version](https://img.shields.io/packagist/v/lcobucci/jose-parsing.svg?style=flat-square)](https://packagist.org/packages/lcobucci/jose-parsing)

![Branch master](https://img.shields.io/badge/branch-master-brightgreen.svg?style=flat-square)
[![Build Status](https://img.shields.io/travis/lcobucci/jose-parsing/master.svg?style=flat-square)](http://travis-ci.org/#!/lcobucci/jose-parsing)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/lcobucci/jose-parsing/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/lcobucci/jose-parsing/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/lcobucci/jose-parsing/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/lcobucci/jose-parsing/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ff4b3454-77ae-4882-8955-0c55c5ce5c68/mini.png)](https://insight.sensiolabs.com/projects/ff4b3454-77ae-4882-8955-0c55c5ce5c68)

A basic Base64Url and json encoding/decoding implementation (requires PHP 7).

## Installation

Package is available on [Packagist](http://packagist.org/packages/lcobucci/jose-parsing),
you can install it using [Composer](http://getcomposer.org).

```shell
composer require lcobucci/jose-parsing
```

### Dependencies

- PHP 7

## Basic usage

This library is pretty simple and I think you would probably not use it without the other related projects :smile:.
It just offers base64url and JSON (with some extra validation) encoding/decoding.

### Encoding

The code is self-explaining:

```php
use Lcobucci\Jose\Parsing\Encoder;

$bilboQuote = "It’s a dangerous business, Frodo, going out your door. You step "
            . "onto the road, and if you don't keep your feet, there’s no knowing "
            . "where you might be swept off to.";
            
$encoder = new Encoder();
echo $encoder->jsonEncode(['testing' => 'test']); // {"testing":"test"}
echo $encoder->base64UrlEncode($bilboQuote);

/*
  That quote is used in RFC 7520 example (in UTF-8 encoding, sure),
  and the output is (line breaks added for readbility):

  SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IHlvdXIgZG9vci4gWW
  91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBkb24ndCBrZWVwIHlvdXIgZmVldCwgdGhl
  cmXigJlzIG5vIGtub3dpbmcgd2hlcmUgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4
 */ 
```

### Decoding

The code is self-explaining (same as above):

```php
use Lcobucci\Jose\Parsing\Decoder;

$bilboQuote = "SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IHlvdXIgZG9vci4gWW"
            . "91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBkb24ndCBrZWVwIHlvdXIgZmVldCwgdGhl"
            . "cmXigJlzIG5vIGtub3dpbmcgd2hlcmUgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4";

$decoder = new Decoder();
var_dump($decoder->jsonDecode('{"testing":"test"}')); // object(stdClass)#1 (1) { ["testing"] => string(4) "test" }
echo $decoder->base64UrlDecode($bilboQuote);

/*
  The output would be the same as the quote used in previous example:

  "It’s a dangerous business, Frodo, going out your door. You step " 
  "onto the road, and if you don't keep your feet, there’s no knowing " 
  "where you might be swept off to."
 */ 
```
