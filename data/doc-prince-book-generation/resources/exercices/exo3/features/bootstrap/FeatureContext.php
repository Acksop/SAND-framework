<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\Step;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{

    /**
     * @Given /^que je suis connecté en tant que "([^"]*)"$/
     */
    public function queJeSuisConnecteEnTantQue($username)
    {
        return array(
            new Step\Given('I go to "login.php"')
            , new Step\When("I fill in \"Identifiant\" with \"$username\"")
            , new Step\When('I press "Se connecter"')
        );
    }

    /**
     * @Given /^que j\'ai "([^"]*)" euro$/
     */
    public function queJAiEuro($balance)
    {
        return array(
            new Step\Given('I go to "/"')
            , new Step\When("I fill in \"Nouveau solde\" with \"$balance\"")
            , new Step\When('I press "Reinitialiser"')
        );
    }

}
