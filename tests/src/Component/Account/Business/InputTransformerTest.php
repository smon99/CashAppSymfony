<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Business;

use App\Component\Account\Business\InputTransformer;
use PHPUnit\Framework\TestCase;

class InputTransformerTest extends TestCase
{
    private InputTransformer $inputTransformer;

    protected function setUp(): void
    {
        $this->inputTransformer = new InputTransformer();
    }

    public function testTransformInput(): void
    {
        $input = '1000';
        $output = $this->inputTransformer->transformInput($input);

        self::assertSame(1000.00, $output);

        $input = '1.000,00';
        $output = $this->inputTransformer->transformInput($input);

        self::assertSame(1000.00, $output);

        $input = '1.000';
        $output = $this->inputTransformer->transformInput($input);

        self::assertSame(1000.00, $output);
    }
}