<?php

declare(strict_types=1);

namespace Tests;

use Composer\InstalledVersions;
use PHPUnit\Framework\TestCase;

/**
 * Versions minimales des dépendances : Symfony 6.4 LTS (les branches 2.8, 3.4
 * et 4.4 sont bloquées par les avis de sécurité de Composer) et illuminate/view 12.
 */
final class DependancesTest extends TestCase
{
    /**
     * @return array<string, array{string, string}>
     */
    public function paquets(): array
    {
        return [
            'routing' => ['symfony/routing', '6.4.0'],
            'http-foundation' => ['symfony/http-foundation', '6.4.0'],
            'yaml' => ['symfony/yaml', '6.4.0'],
            'config' => ['symfony/config', '6.4.0'],
            'expression-language' => ['symfony/expression-language', '6.4.0'],
            'illuminate/view' => ['illuminate/view', '12.0.0'],
        ];
    }

    /**
     * @dataProvider paquets
     */
    public function testVersionMinimale(string $paquet, string $minimum): void
    {
        $this->assertTrue(InstalledVersions::isInstalled($paquet), $paquet . ' absent.');
        $version = InstalledVersions::getVersion($paquet);
        $this->assertTrue(
            version_compare($version, $minimum, '>='),
            sprintf('%s %s < %s', $paquet, $version, $minimum)
        );
    }
}
