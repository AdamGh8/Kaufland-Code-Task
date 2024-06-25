<?php

namespace App\Contracts;

interface DataParserInterface
{
    public function parse(string $dataSource): array;
}
