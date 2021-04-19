<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Exemple de test sur un cas simple
 * @package Tests
 */
final class UrlTest extends TestCase
{
    public function testCompareCharge(): void
    {
        $baseDirectory = \MVC\Classe\Url::getBaseDirectory();
        if('/' . BASE_SERVER_DIRECTORY == $baseDirectory) {
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
    }
}
