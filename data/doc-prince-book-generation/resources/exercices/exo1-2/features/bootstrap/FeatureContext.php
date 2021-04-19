<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{

    private $birthDate;
    private $today;
    private $output;

    /**
     * @Given /^que je suis né le "([^"]*)"$/
     */
    public function queJeSuisNeLe($date)
    {
        $this->birthDate = DateTime::createFromFormat('d/m/Y', $date);
    }

    /**
     * @Given /^que nous sommes le "([^"]*)"$/
     */
    public function queNousSommesLe($date)
    {
        $this->today = DateTime::createFromFormat('d/m/Y', $date);
    }

    /**
     * @Given /^je calcule mon âge$/
     */
    public function jeCalculeMonAge()
    {
        $this->output = shell_exec(sprintf('php src/age.php --birthdate=%s --today=%s', $this->birthDate->format('Y-m-d'), $this->today->format('Y-m-d')));
    }

    /**
     * @Given /^on me répond "([^"]*)"$/
     */
    public function onMeRepond($response)
    {
        if (false === strpos($this->output, $response)) {
            throw new Exception;
        }
    }

}
