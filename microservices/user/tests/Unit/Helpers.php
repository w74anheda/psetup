<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class Helpers extends TestCase
{

    public function testGenerateRandomDigitsWithSpeceficLength(): void
    {
        $this->assertTrue(
            function_exists('generate_random_digits_with_specefic_length')
        );

        $length = rand(5, 15);
        $code   = generate_random_digits_with_specefic_length($length);

        $this->assertIsInt($code);
        $this->assertTrue(strlen((string) $code) == $length);

    }


    public function testValidateNationalId(): void
    {
        $this->assertTrue(
            function_exists('validate_national_id')
        );

        $this->assertFalse(
            validate_national_id(generate_random_digits_with_specefic_length(10))
        );

        $this->assertIsBool(
            validate_national_id(generate_random_digits_with_specefic_length(10))
        );

        $this->assertTrue(
            validate_national_id('1741995108')
        );

        $this->assertIsBool(
            validate_national_id('1741995108')
        );

        $this->assertFalse(
            validate_national_id('1741995108','EN')
        );

        $this->assertTrue(
            validate_national_id('1741995108','IR')
        );

    }
}
