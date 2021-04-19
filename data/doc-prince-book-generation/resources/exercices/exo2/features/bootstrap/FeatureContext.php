<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Step;

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{

    /**
     * @When /^j\'ajoute dans mon panier "([^"]*)"  depuis le catalogue produit$/
     */
    public function jAjouteDansMonPanierDepuisLeCatalogueProduit($produit)
    {
        return array(
            new Step\Given('que je consulte le catalogue produit')
            , new Step\When(sprintf('j\'ajoute dans mon panier "%s"', $produit))
        );
    }

    /**
     * @Given /^que je consulte le catalogue produit$/
     */
    public function queJeConsulteLeCatalogueProduit()
    {
        return array(
            new Step\Given('que je suis sur la page "/catalogue/produits"')
        );
    }

    /**
     * @When /^j\'ajoute dans mon panier "([^"]*)"$/
     */
    public function jAjouteDansMonPanier($produit)
    {
        return array(
            new Step\When(sprintf('je coche "%s"', $produit))
            , new Step\When('je clique sur "Ajouter au panier"')
        );
    }

    /**
     * @Given /^que je suis sur la page "([^"]*)"$/
     */
    public function queJeSuisSurLaPage($url)
    {
        // utiliser Mink
    }

    /**
     * @When /^je coche "([^"]*)"$/
     */
    public function jeCoche($produit)
    {
        // utiliser Mink
    }

    /**
     * @When /^je clique sur "([^"]*)"$/
     */
    public function jeCliqueSur($bouton)
    {
        // utiliser Mink
    }

}
