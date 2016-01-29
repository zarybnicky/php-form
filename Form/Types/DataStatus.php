<?php
namespace Olc\Form\Types;

use Olc\Data\Enum;

class DataStatus extends Enum
{
    const USER = 'user-submitted';
    const INITIAL = 'default';
    const MISSING = 'missing';
}
