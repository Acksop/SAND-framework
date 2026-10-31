<?php

/**
 * Package MVC\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace MVC\Classe;

class Modele
{
    /**
     * Ligne « cle : valeur » d'un .model : clé stricte, valeur libre (tout
     * caractère hors retour à la ligne, le premier « : » sépare la clé).
     */
    private const MOTIF_LIGNE = '#^[ \t]*([a-zA-Z_+]+)[ \t]*:([^\r\n]*)#';

    public $page;

    /**
     * @param array       $base_param nom de la page (`name`) et paramètres d'URL (`params`)
     * @param string|null $dossier    dossier des .model (défaut : MODELS_PATH)
     */
    public function __construct($base_param, ?string $dossier = null)
    {
        $modele = ($dossier ?? MODELS_PATH) . DIRECTORY_SEPARATOR . $base_param['name'] . '.model';
        if (file_exists($modele)) {
            $fichier = file($modele);
            foreach ($fichier as $ligne_num => $ligne) {
                //on recherche le pattern des parametres
                if (preg_match(self::MOTIF_LIGNE, $ligne, $matches)) {
                    $cle = trim($matches[1]);
                    $this->page[$cle] = self::analyserValeur($matches[2]);
                    if (is_array($this->page[$cle]) && !self::crochetsEquilibres($matches[2])) {
                        Logger::addLog('modele', '[Modele] ' . basename($modele)
                            . ' : crochets mal formés dans la valeur de « ' . $cle . ' »');
                    }
                }
            }

            $this->page['all_params'] = $base_param['params'];
            //export nom a nom les variable dans la superglobale $_GET
            foreach($base_param['params'] as $key => $value){
                $_GET[$key] = $value;
            }
        } else {
            $this->page['name'] = $base_param['name'];
            $this->page['description'] = $base_param['description'];
            $this->page['all_params'] = $base_param['params'];
            //export nom a nom les variable dans la superglobale $_GET
            foreach($base_param['params'] as $key => $value){
                $_GET[$key] = $value;
            }
        }
        return $this;
    }

    /**
     * Valeur d'un paramètre de .model.
     *
     * - `texte` : la chaîne, sans espaces autour ;
     * - `{a, b}` : liste de chaînes ;
     * - dans `{…}`, chaque élément `[…]` devient un sous-tableau : `[]` → `[]`,
     *   `[cle=valeur, cle2=valeur2]` → tableau associatif, `[a, b]` → liste.
     *
     * Un seul niveau de sous-tableau : un `[` à l'intérieur d'un `[…]` reste du texte.
     *
     * @return string|array
     */
    public static function analyserValeur(string $valeur)
    {
        $valeur = trim($valeur);
        if (!preg_match('#^\{(.*)\}$#s', $valeur, $accolades)) {
            return $valeur;
        }

        $tableau = [];
        foreach (self::decouperElements($accolades[1]) as $element) {
            $element = trim($element);
            if ($element === '') {
                continue;
            }
            if ($element[0] === '[' && substr($element, -1) === ']') {
                $tableau[] = self::analyserSousTableau(substr($element, 1, -1));
            } else {
                $tableau[] = $element;
            }
        }

        return $tableau;
    }

    /**
     * Faux si un « ] » n'a pas de « [ » ouvrant ou si un « [ » reste ouvert.
     */
    private static function crochetsEquilibres(string $valeur): bool
    {
        $profondeur = 0;
        foreach (str_split($valeur) as $octet) {
            if ($octet === '[') {
                $profondeur++;
            } elseif ($octet === ']' && --$profondeur < 0) {
                return false;
            }
        }

        return $profondeur === 0;
    }

    /**
     * Découpe le contenu d'un {…} sur les virgules situées hors des […].
     * Lecture octet par octet : « [ », « ] » et « , » sont en ASCII et
     * n'apparaissent jamais dans un caractère UTF-8 multi-octet.
     */
    private static function decouperElements(string $contenu): array
    {
        $elements = [];
        $courant = '';
        $profondeur = 0;
        foreach (str_split($contenu) as $octet) {
            if ($octet === '[') {
                $profondeur++;
            } elseif ($octet === ']' && $profondeur > 0) {
                $profondeur--;
            } elseif ($octet === ',' && $profondeur === 0) {
                $elements[] = $courant;
                $courant = '';
                continue;
            }
            $courant .= $octet;
        }
        $elements[] = $courant;

        return $elements;
    }

    /**
     * Contenu d'un […] : `cle=valeur` donne une entrée associative, un élément
     * sans `=` une entrée de liste ; les éléments vides sont ignorés.
     */
    private static function analyserSousTableau(string $contenu): array
    {
        $sousTableau = [];
        foreach (explode(',', $contenu) as $morceau) {
            $morceau = trim($morceau);
            if ($morceau === '') {
                continue;
            }
            $egal = strpos($morceau, '=');
            if ($egal === false) {
                $sousTableau[] = $morceau;
            } else {
                $sousTableau[trim(substr($morceau, 0, $egal))] = trim(substr($morceau, $egal + 1));
            }
        }

        return $sousTableau;
    }
}
