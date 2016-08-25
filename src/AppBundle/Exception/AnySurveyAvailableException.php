<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AnySurveyAvailableException extends HttpException
{
    const ERROR_CODE = 403;
    const ERROR_MESSAGE = 'Any survey is available yet.';

    public function __construct()
    {
        parent::__construct(self::ERROR_CODE, self::ERROR_MESSAGE);
    }
}