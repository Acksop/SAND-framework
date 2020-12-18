<?php

namespace App\Session\AuthBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SessionAuthExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configs[0]['environment'] = $container->getParameter("kernel.environment");
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        //Chargement des parametres
        $loader->load('parameters.yml');
        //Chargement des services
        $loader->load('services.yml');


        //definition du service d'authentification par défaut dans le cas où ce ne serait pas un service
        // fraichement créé par l'utilisateur dans le fichiers services.yaml
        if (is_null($config["authentication_service"])) {
            $authentication_service = "session_auth.authentification";
        } else {
            $authentication_service = $config["authentication_service"];
        }

        if ($authentication_service == "session_auth.authentification") {
            $container->register($authentication_service, \App\Besancon\AuthBundle\Security\DefaultAuthentication::class)
                ->addMethodCall('setGetterAttributes', array($config))
                ->setPublic(false);
        }

        //Creation du service @bes_auth.authenticator permettant la redirection sur le Cas ou le Rsa correspondant
        $container->register('session_auth.authenticator', \Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator::class)
            ->setFactory(array(new Reference("session_auth.authenticator_factory"), 'getAuthenticator'))
            ->addArgument(new Reference($authentication_service))
            ->addArgument($config)
            ->addArgument(new Reference("router"))
            ->addArgument(new Reference("event_dispatcher"))
            ->setPublic(false);


        //Création du service pour le provider par défaut ou pour le provider défini par l'utilisateur
        if ($config["use_default_provider"]) {
            //Creation du service @bes_auth.user_provider
            $container->register('session_auth.user_provider', \App\Besancon\AuthBundle\Security\User\AuthUserProvider::class)
                ->addArgument(new Reference($authentication_service))
                ->addArgument($config)
                ->setPublic(false);
        } else {
            $container->register('session_auth.user_provider', $config["provider"])
                ->addArgument(new Reference($authentication_service))
                ->addArgument($config)
                ->setPublic(false);
        }

        $container->setDefinition('session_auth.configuration', new \Symfony\Component\DependencyInjection\Definition(\App\Besancon\AuthBundle\DependencyInjection\Configuration::class))
            ->setArguments([
                $config,
            ]);
    }

    public function getNamespace()
    {
        return 'http://ac-besancon.fr/schema/dic/' . $this->getAlias();
    }
}
