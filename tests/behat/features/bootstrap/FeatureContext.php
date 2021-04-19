<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given je suis connecté en tant que :arg1
     */
    public function jeSuisConnecteEnTantQue($arg1)
    {
        // Choose a Mink driver. More about it in later chapters.
        $driver = new \Behat\Mink\Driver\GoutteDriver();
        $session = new \Behat\Mink\Session($driver);
        // start the session
        $session->start();
        $session->visit('http://sand-framework.dev.wm/');
        $page = $session->getPage();
        $connectedText = $page->find('xpath', '/html/body/div/div[2]/div/span[1]');
        if($connectedText !== "Vous êtes connecté en tant que $arg1") {
            throw new PendingException();
        }
    }

    /**
     * @Given je suis sur :arg1
     */
    public function jeSuisSur($arg1)
    {
        $session = $this->getMink()->getSession();
        $url = $session->getCurrentUrl();
        if($url !== "http://sand-framework.dev.wm$arg1") {
            throw new PendingException();
        }
    }

    /**
     * @When je clique sur :arg1
     */
    public function jeCliqueSur($arg1)
    {
        $link_tab = array(
            'Documentation' => 1,
            'Dépot' => 2,
            'Donate' => 3,
            'CGU Terms' => 4,
            'Policy' => 5
        );

        $session = $this->getMink()->getSession();
        $page = $session->getPage();
        $page->find('xpath', "/html/body/div/div[1]/div/ul/li[{$link_tab[$arg1]}]/a")->click();
    }

    /**
     * @Then je devrais voir :arg1
     */
    public function jeDevraisVoir($arg1)
    {
        $link_tab = array(
            'Sommaire' => '/html/body/div/section/div/h1',
            'GitList' => '/html/body/div/section/div/div[1]/div/div/a[2]',
            'Become a Sponsor !' => '//*[@id="donate"]',
            'Conditions Générale de l\'application' => '/html/body/div/section/div/div/h1',
            'Politique Générale de Sécurité' => '/html/body/div/section/div/div/h1'
        );
        $session = $this->getMink()->getSession();
        $page = $session->getPage();

        $connectedText = $page->find('xpath', $link_tab[$arg1]);
        if($connectedText !== $arg1) {
            throw new PendingException();
        }
    }
}
