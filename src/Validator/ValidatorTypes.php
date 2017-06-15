<?php

namespace Frobou\Validator;

use CommerceGuys\Enum\AbstractEnum;

final class ValidatorTypes extends AbstractEnum
{
    const EXISTS = 0;
    const NOTFOUND = 1;
    const ISNULL = 2;
    const ERROR = 3;
}
