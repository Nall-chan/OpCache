<?php

declare(strict_types=1);

include_once __DIR__ . '/stubs/Validator.php';

class LibraryValidationTest extends TestCaseSymconValidation
{
    public function testValidateLibrary(): void
    {
        $this->validateLibrary(__DIR__ . '/..');
    }

    public function testValidateOpCacheModule(): void
    {
        $this->validateModule(__DIR__ . '/../OpCacheModule');
    }

    public function testValidateOpCacheInfoSite(): void
    {
        $this->validateModule(__DIR__ . '/../OpCacheInfoSite');
    }
}