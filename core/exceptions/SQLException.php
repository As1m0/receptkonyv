<?php

class SQLException extends Exception
{
    public function __construct(string $message, ?\Throwable $previous)
    {
        return parent::__construct($message, 0, $previous);
    }
}