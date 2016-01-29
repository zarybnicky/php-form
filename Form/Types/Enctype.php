<?php
namespace Olc\Form\Types;

use Olc\Data\Enum;
use Olc\Data\Monoid;

class Enctype extends Enum
{
    const URL_ENCODED = 'application/x-www-form-urlencoded';
    const MULTIPART = 'multipart/form-data';

    public static function getMonoid()
    {
        $urlEncoded = self::URL_ENCODED();
        $multipart = self::MULTIPART();

        return new Monoid(
            $urlEncoded,
            function ($x, $y) use ($urlEncoded, $multipart) {
                return ($multipart->is($x) || $multipart->is($y))
                    ? $multipart
                    : $urlEncoded;
            }
        );
    }
}
