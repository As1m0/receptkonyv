<?php

class VisitLoggerModule implements IModuleBase
{
    public function Run(array $data = []): void
    {
        Logger::WriteLog("Oldal meghívás történt ({$data["page"]})", LogLevel::Info);
    }
}
