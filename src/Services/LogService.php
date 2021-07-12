<?php

namespace Services;

use Entities\Log;
use Entities\Magazine;
use Entities\Product;
use Repositories\LogRepository;

class LogService
{
    private LogRepository $logRepository;

    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function log(\Throwable $e = null): void
    {
        $message = $e ? $e->getMessage() : null;
        $log = new Log($message);
        $this->logRepository->persist($log);
        $this->logRepository->flush();
    }

    public function printMagazineMessage(Magazine $magazine): void
    {
        $publicationCode = $magazine->getPublicationCode();
        print_r("Magazine with publicationCode {$publicationCode} parsed" . PHP_EOL);
    }
}