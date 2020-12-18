<?php

namespace App\Session\AuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('session_auth');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
            ->scalarNode('homepage')->defaultNull()->end()
            ->scalarNode('authentication_service')->defaultNull()->end()
            ->scalarNode('provider')->defaultNull()->end()
            ->booleanNode('use_default_provider')->defaultTrue()->end()
            ->scalarNode('user_entity')->defaultNull()->end()
            ->scalarNode('type_auth')->isRequired()->cannotBeEmpty()
            ->validate()
            ->ifNotInArray(array('Rsa', 'Cas', 'Session'))
            ->thenInvalid("La méthode d'authentification %s n'est pas gérée, seuls Rsa et Cas sont acceptés")
            ->end()
            ->end()
            ->scalarNode('environment')->end()
            ->end();

        $rootNode
            ->validate()
            ->ifTrue(function ($v) {
                if (!is_null($v['user_entity'])) {
                    $class = $v['user_entity'];
                    if (!class_exists($class)) {
                        return true;
                    }
                    return !array_key_exists("Symfony\Component\Security\Core\User\UserInterface", class_implements($class));
                }
                return false;
            })
            ->thenInvalid("La classe renseignée pour 'entity' doit implémenter Symfony\Component\Security\Core\User\UserInterface")
            ->end();

        $this->_addCasConfig($rootNode);
        $this->_addRsaConfig($rootNode);

        return $treeBuilder;
    }

    private function _addCasConfig(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->arrayNode('cas')->info('A déclarer si authentification pas CAS.')
            ->addDefaultsIfNotSet()
            ->treatNullLike(['hostname' => null])
            ->treatNullLike(['port' => null])
            ->treatNullLike(['uri' => null])
            ->children()
            ->scalarNode('hostname')->defaultNull()->end()
            ->scalarNode('port')->defaultNull()->end()
            ->scalarNode('uri')->defaultNull()->end()
            ->end()
            ->end()
            ->end();

        $node
            ->validate()
            ->ifTrue(function ($v) {
                $cas_config = $v['cas'];
                return ($v['type_auth'] == "Cas" && (is_null($cas_config['hostname']) || is_null($cas_config['port']) || is_null($cas_config['uri'])));
            })
            ->thenInvalid("En utilisant le type d'authentification Cas vous devez renseigner la section 'cas' et ses clés 'hostname', 'port', 'uri'")
            ->end();
    }

    private function _addRsaConfig(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->arrayNode('rsa')->addDefaultsIfNotSet()->info('A déclarer si authentification pas RSA.')
            ->addDefaultsIfNotSet()
            ->treatNullLike(['logout_url' => null])
            ->children()
            ->scalarNode('logout_url')->defaultNull()->end()
            ->end()
            ->end()
            ->end();

        $node
            ->validate()
            ->ifTrue(function ($v) {
                $rsa_config = $v['rsa'];
                return ($v['type_auth'] === "Rsa" && is_null($rsa_config['logout_url']));
            })
            ->thenInvalid("En utilisant le type d'authentification Rsa vous devez renseigner la section 'rsa' et sa clé 'logout_url'")
            ->end();
    }
}
