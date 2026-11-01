<?php

/**
 * Connexions BDD autorisées pendant les tests (BddTest, SQLite en mémoire),
 * en plus des connexions par défaut. Chargé par amorce.php avant l'autoload.
 */

define('BDD_CONNEXIONS_AUTORISEES', [
    'default', 'bdd1', 'bdd2', 'testsqlite', 'bdd7', 'bdd8', 'prioritetest', 'sansutilisateur', 'cassee', 'absente',
]);
