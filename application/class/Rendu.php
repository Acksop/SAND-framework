<?php

/**
 * Package MVC\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace MVC\Classe;

use Illuminate\View\Factory;
use Twig\Environment;
use Windwalker\Renderer\BladeRenderer;
use Windwalker\Renderer\RendererInterface;
use Windwalker\Renderer\TwigRenderer;

/**
 * Prépare les moteurs Blade et Twig (windwalker/renderer 4) utilisés par
 * Vue et Implement\Action.
 *
 * Les gabarits sont cherchés dans l'ordre view > layout > system sous la
 * racine des vues. Blade compile dans un dossier de cache ; Twig n'en a pas,
 * comme avant la montée de version.
 */
class Rendu
{
    /**
     * Rend un gabarit avec le moteur demandé : 'blade' ou 'twig'.
     *
     * Pour Twig, le nom est donné sans extension : « index » charge index.html.twig.
     *
     * @throws \InvalidArgumentException si le moteur est inconnu
     */
    public static function rendre(
        string $moteur,
        string $nom,
        array $donnees,
        string $racine = VIEW_PATH,
        ?string $cache = null
    ): string {
        switch ($moteur) {
            case 'twig':
                return self::twig($racine)->render($nom . '.html.twig', $donnees);
            case 'blade':
                return self::blade($racine, $cache)->render($nom, $donnees);
            default:
                throw new \InvalidArgumentException(
                    sprintf('Moteur de rendu inconnu « %s » : attendu « blade » ou « twig ».', $moteur)
                );
        }
    }

    /**
     * Moteur Twig 3 avec les fonctions action, call, session et server.
     */
    public static function twig(string $racine = VIEW_PATH): RendererInterface
    {
        $moteur = new TwigRenderer(['paths' => self::chemins($racine)]);
        $moteur->extend(
            static function (Environment $twig): Environment {
                $twig->addExtension(new TwigControlleurAction());

                return $twig;
            }
        );

        return $moteur;
    }

    /**
     * Moteur Blade (illuminate/view) avec cache de compilation.
     *
     * {{ }} affiche sans échappement et {{{ }}} échappe, comme avec
     * illuminate/view 4 : les gabarits existants gardent leur rendu.
     */
    public static function blade(string $racine = VIEW_PATH, ?string $cache = null): RendererInterface
    {
        $moteur = new BladeRenderer([
            'paths' => self::chemins($racine),
            'cache_path' => $cache ?? $racine . DIRECTORY_SEPARATOR . 'cache',
        ]);
        $moteur->extend(
            static function (Factory $fabrique): Factory {
                $fabrique->getEngineResolver()->resolve('blade')->getCompiler()->setEchoFormat('%s');

                return $fabrique;
            }
        );

        return $moteur;
    }

    /**
     * Dossiers de gabarits, du plus prioritaire au moins prioritaire.
     *
     * @return string[]
     */
    public static function chemins(string $racine = VIEW_PATH): array
    {
        return [
            $racine . DIRECTORY_SEPARATOR . 'view',
            $racine . DIRECTORY_SEPARATOR . 'layout',
            $racine . DIRECTORY_SEPARATOR . 'system',
        ];
    }
}
