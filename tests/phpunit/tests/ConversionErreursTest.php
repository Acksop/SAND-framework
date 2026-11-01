<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Error\Deprecated;
use PHPUnit\Framework\Error\Warning;
use PHPUnit\Framework\TestCase;

/**
 * Garde permanente : PHPUnit doit convertir dépréciations et avertissements
 * en exceptions. Si un gestionnaire d'erreurs reste installé par l'amorce,
 * PHPUnit n'installe pas le sien et ces tests échouent.
 */
final class ConversionErreursTest extends TestCase
{
    public function testUneDepreciationLeveUneException(): void
    {
        try {
            trigger_error('dépréciation de garde', E_USER_DEPRECATED);
        } catch (Deprecated $exception) {
            $this->assertSame('dépréciation de garde', $exception->getMessage());

            return;
        }

        $this->fail('E_USER_DEPRECATED non converti : un gestionnaire d\'erreurs masque celui de PHPUnit.');
    }

    public function testUnAvertissementLeveUneException(): void
    {
        try {
            trigger_error('avertissement de garde', E_USER_WARNING);
        } catch (Warning $exception) {
            $this->assertSame('avertissement de garde', $exception->getMessage());

            return;
        }

        $this->fail('E_USER_WARNING non converti : un gestionnaire d\'erreurs masque celui de PHPUnit.');
    }
}
