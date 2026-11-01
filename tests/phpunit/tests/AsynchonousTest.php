<?php

declare(strict_types=1);

namespace Tests;

use SAND\Classe\Asynchonous;
use PHPUnit\Framework\TestCase;

/**
 * addJs()/printJs() : alias dépréciés de addFooterJs()/printFooterJs().
 *
 * Les dépréciations sont relevées par un gestionnaire local : expectDeprecation()
 * est lui-même déprécié dans PHPUnit 9.6 (avertissement).
 */
final class AsynchonousTest extends TestCase
{
    public function testAddJsEmetUneDepreciation(): void
    {
        $depreciations = $this->depreciationsDe(static function (): void {
            (new Asynchonous())->addJs('alert(1);');
        });

        $this->assertCount(1, $depreciations);
        $this->assertMatchesRegularExpression('/addJs\(\).*addFooterJs\(\)/', $depreciations[0]);
    }

    public function testPrintJsEmetUneDepreciation(): void
    {
        $depreciations = $this->depreciationsDe(static function (): void {
            (new Asynchonous())->printJs();
        });

        $this->assertCount(1, $depreciations);
        $this->assertMatchesRegularExpression('/printJs\(\).*printFooterJs\(\)/', $depreciations[0]);
    }

    public function testLesAliasEcriventEtLisentLeTamponDePied(): void
    {
        $sorties = [];
        $depreciations = $this->depreciationsDe(static function () use (&$sorties): void {
            $asynchrone = new Asynchonous();
            $asynchrone->addJs('alert(1);');
            $asynchrone->addFooterJs('alert(2);');
            foreach (['printJs', 'printFooterJs', 'printHeaderJs'] as $methode) {
                ob_start();
                $asynchrone->$methode();
                $sorties[$methode] = ob_get_clean();
            }
        });

        $this->assertSame("\nalert(1);\nalert(2);", $sorties['printJs']);
        $this->assertSame($sorties['printFooterJs'], $sorties['printJs']);
        $this->assertSame('', $sorties['printHeaderJs']);
        $this->assertCount(2, $depreciations);
    }

    /**
     * Exécute $code et renvoie les messages E_USER_DEPRECATED émis.
     */
    private function depreciationsDe(callable $code): array
    {
        $depreciations = [];
        set_error_handler(static function (int $niveau, string $message) use (&$depreciations): bool {
            $depreciations[] = $message;

            return true;
        }, E_USER_DEPRECATED);

        try {
            $code();
        } finally {
            restore_error_handler();
        }

        return $depreciations;
    }
}
