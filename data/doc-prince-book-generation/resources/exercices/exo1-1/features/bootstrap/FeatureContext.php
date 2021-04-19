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
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        
    }

    /**
     * @Given /^que je suis né le (\d+)\/(\d+)\/(\d+)$/
     */
    public function queJeSuisNeLe($day, $month, $year)
    {
        $this->birthDate = new \DateTime(sprintf('%d-%d-%d', $year, $month, $day));
    }

    /**
     * @Given /^que nous sommes le (\d+)\/(\d+)\/(\d+)$/
     */
    public function queNousSommesLe($day, $month, $year)
    {
        $this->today = new \DateTime(sprintf('%d-%d-%d', $year, $month, $day));
    }

    /**
     * @Given /^je calcule mon âge$/
     */
    public function jeCalculeMonAge()
    {
        $this->output = shell_exec(sprintf('php src/age.php --birthdate=%s --today=%s', $this->birthDate->format('Y-m-d'), $this->today->format('Y-m-d')));
    }

    /**
     * @Given /^je suis informé que j\'ai (\d+) ans$/
     */
    public function jeSuisInformeQueJAiAns($age)
    {
        if(!preg_match('!'.$age.' ans!', $this->output)) {
            throw new Exception();
        }
    }

    /**
     * @Given /^je suis informé que je ne suis pas encore né$/
     */
    public function jeSuisInformeQueJeNeSuisPasEncoreNe()
    {
        if(!preg_match('!Vous n\'êtes pas encore né!', $this->output)) {
            throw new Exception();
        }
    }

    /**
     * @Given /^on me souhaite un joyeux anniversaire$/
     */
    public function onMeSouhaiteUnJoyeuxAnniversaire()
    {
        if(!preg_match('!Joyeux anniversaire!', $this->output)) {
            throw new Exception();
        }
    }

}
