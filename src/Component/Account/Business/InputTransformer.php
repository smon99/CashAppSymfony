<?php declare(strict_types=1);

namespace App\Component\Account\Business;

class InputTransformer
{
    public function transformInput(string $input): float
    {
        $amount = str_replace(['.', ','], ['', '.'], $input);
        return (float)$amount;
    }
}