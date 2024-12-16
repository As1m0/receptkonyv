<?php

class SearchKeyLoggerModule implements IModuleBase
{
    public function Run(array $data = []): void
    {
        global $cfg;
        Logger::WriteSearchLog("{$data["searcKey"]}");
    }
}
