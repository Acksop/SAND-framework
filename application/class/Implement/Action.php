<?php

namespace MVC\Classe\Implement;

use MVC\Classe\Rendu;

class Action
{
    /**
     * Rend un gabarit d'action ; Blade par défaut, Twig si $engine vaut 'twig'.
     */
    public function render($view, $data, $engine = 'blade')
    {
        if ($engine === 'twig') {
            return $this->renderTwig($view, $data);
        }

        return $this->renderBlade($view, $data);
    }

    public function renderJSON($json)
    {
        //header('Content-Type: application/json; charset=utf-8');
        return json_encode($json, JSON_HEX_APOS);
    }

    /**
     * Rend un gabarit Twig. $dir (facultatif) remplace racineVues() pour cet
     * appel : par exemple le dossier « vues » d'un module.
     */
    public function renderTwig($view, $data, ?string $dir = null)
    {
        return Rendu::rendre('twig', $view, $data, $dir ?? $this->racineVues());
    }

    /**
     * Rend un gabarit Blade. Avec $dir, les gabarits sont lus sous $dir et
     * compilés dans $dir/cache ; sans $dir, racineVues() et cheminCache().
     */
    public function renderBlade($view, $data, ?string $dir = null)
    {
        if ($dir !== null) {
            return Rendu::rendre('blade', $view, $data, $dir, $dir . DIRECTORY_SEPARATOR . 'cache');
        }

        return Rendu::rendre('blade', $view, $data, $this->racineVues(), $this->cheminCache());
    }

    /**
     * Racine des gabarits (sous-dossiers view, layout, system).
     */
    protected function racineVues(): string
    {
        return VIEW_PATH;
    }

    /**
     * Dossier de cache de compilation Blade.
     */
    protected function cheminCache(): string
    {
        return VIEW_PATH . DIRECTORY_SEPARATOR . 'cache';
    }
}
