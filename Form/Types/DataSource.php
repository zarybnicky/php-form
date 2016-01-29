<?php
namespace Olc\Form\Types;

use Olc\Data\Enum;

class DataSource extends Enum
{
    const SERVER = '_SERVER';
    const GET = '_GET';
    const POST = '_POST';
    const FILES = '_FILES';
    const COOKIE = '_COOKIE';
    const SESSION = '_SESSION';
    const ENV = '_ENV';
}
