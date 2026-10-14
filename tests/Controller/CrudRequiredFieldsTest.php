<?php

namespace Base\Press\Tests\Controller;

use Base\Admin\Test\CrudFieldsTestCase;
use Base\Press\Controller\Admin\Crud\PhotoCrudController;

/**
 * The fields marked required on this bundle's back office screens, against
 * what its records need (see CrudFieldsTestCase): only those the record
 * cannot do without, all of them, an optional one able to take an empty
 * value, one field per property, a slug following its title.
 */
final class CrudRequiredFieldsTest extends CrudFieldsTestCase
{
    protected static function sourceDirectory(): string
    {
        return __DIR__.'/../../src';
    }

    protected static function requiredByTheScreen(): array
    {
        return [
            PhotoCrudController::class => [
                'file' => 'a press photo is its file (required on a new one only: an edited one keeps its file)',
            ],
        ];
    }
}
