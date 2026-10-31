<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Url;
use PHPUnit\Framework\TestCase;

/**
 * Caractérisation des liens de Url (API publique conservée après
 * l'extraction dans UrlLiens).
 */
final class UrlLiensTest extends TestCase
{
    /** @var array */
    private $serveurAvant;

    protected function setUp(): void
    {
        $this->serveurAvant = $_SERVER;
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_SCHEME'] = 'http';
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->serveurAvant;
    }

    public function testLiensRelatifs(): void
    {
        $base = '/' . BASE_SERVER_DIRECTORY;

        $this->assertSame($base . 'css/a.css', Url::asset_rewrite('css/a.css'));
        $this->assertSame($base . 'page/a/1/b/2', Url::link_rewrite(false, 'page', ['a' => 1, 'b' => 2]));
        $this->assertSame($base . 'control/form/a/1', Url::link_rewrite(true, 'form', ['a' => 1]));
        $this->assertSame($base . 'control/form', Url::link_rewrite(true, 'form', null));
        $this->assertSame($base, Url::getBaseDirectory());
    }

    public function testLiensAbsolus(): void
    {
        $hote = explode('://', PATH_URL)[0] . '://localhost';

        $this->assertSame($hote . '/' . BASE_SERVER_DIRECTORY . 'page/a/1', Url::absolute_link_rewrite(false, 'page', ['a' => 1]));
        $this->assertSame($hote . '/' . BASE_SERVER_DIRECTORY . 'control/form', Url::absolute_link_rewrite(true, 'form'));
        $this->assertSame(rtrim($hote . '/' . BASE_SERVER_DIRECTORY, '/'), Url::getRootDirectoryUrl());
    }

    public function testSchemaDePathUrlPrioritaire(): void
    {
        $_SERVER['REQUEST_SCHEME'] = 'gopher';

        $this->assertStringStartsWith(explode('://', PATH_URL)[0] . '://localhost/', Url::absolute_link_rewrite(false, 'p'));
    }

    public function testSansHoteHttpPathUrl(): void
    {
        unset($_SERVER['HTTP_HOST']);

        $this->assertSame(rtrim(PATH_URL, '/') . '/' . BASE_SERVER_DIRECTORY . 'p', Url::absolute_link_rewrite(false, 'p'));
    }
}
