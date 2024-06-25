<?php

namespace App\Contracts;

interface StorageInterface
{
    public function storeItem(array $item);
}
