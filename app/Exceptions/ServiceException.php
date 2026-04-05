<?php

namespace App\Exceptions;

use Exception;

class ServiceException extends Exception
{
    protected $errors;
    protected $status;

    public function __construct(
        string $message = 'Erro interno',
        array $errors = [],
        int $status = 400
    ) {
        parent::__construct($message);
        $this->errors = $errors;
        $this->status = $status;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getStatus()
    {
        return $this->status;
    }
}