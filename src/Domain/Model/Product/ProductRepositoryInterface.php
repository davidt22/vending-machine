<?php

namespace App\Domain\Model\Product;

interface ProductRepositoryInterface
{
    public function nextIdentity(): ProductId;
}