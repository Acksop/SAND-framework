<?php

/**
 * Package SAND\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace SAND\Api\Component;

/**
 * Registre de routes explicites, équivalent de register_rest_route() de WordPress,
 * pour les points d'entrée hors de la convention /{namespace}/{resource}/{id?}.
 * Ces routes sont essayées avant la convention.
 */
final class RouteRegistry
{
    /** @var array<int, array{methode: string, regex: string, rappel: callable}> */
    private array $routes = [];

    /**
     * Charge un fichier de routes : il reçoit le registre dans la variable $registre.
     */
    public static function depuisFichier(?string $fichier): self
    {
        $registre = new self();
        if ($fichier !== null && is_file($fichier)) {
            (static function (RouteRegistry $registre, string $fichier): void {
                require $fichier;
            })($registre, $fichier);
        }

        return $registre;
    }

    /**
     * @param string   $methode GET|POST|PUT|PATCH|DELETE
     * @param string   $motif   expression relative au préfixe, ex. 'v1/dossiers/(?P<id>\d+)/breves'
     * @param callable $rappel  fn(array $params, array $corps): array|Response
     */
    public function register(string $methode, string $motif, callable $rappel): void
    {
        $this->routes[] = [
            'methode' => strtoupper($methode),
            'regex' => '#^' . str_replace('#', '\#', $motif) . '$#',
            'rappel' => $rappel,
        ];
    }

    /**
     * @return array{0: callable, 1: array<string, string>}|null
     */
    public function match(string $methode, string $chemin): ?array
    {
        foreach ($this->routes as $route) {
            if ($route['methode'] === $methode && preg_match($route['regex'], $chemin, $trouve) === 1) {
                $params = array_filter($trouve, static fn ($cle) => !is_int($cle), ARRAY_FILTER_USE_KEY);

                return [$route['rappel'], $params];
            }
        }

        return null;
    }

    public function nombre(): int
    {
        return count($this->routes);
    }
}
