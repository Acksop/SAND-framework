<?php


namespace SAND\Object;


class Ldap
{
    public static function testLdap(){
        echo '<h3>requête de test de LDAP</h3>';

        echo 'Connexion ...';
        $ds = ldap_connect(LDAP_URL,LDAP_PORT);  // doit être un serveur LDAP valide !
        echo 'Le résultat de connexion est ' . $ds . '<br />';

        if ($ds) {
            echo 'Liaison ...';
            $r = ldap_bind_ext($ds, LDAP_USER, LDAP_PASSWORD);
            //$r = ldap_bind($ds);     // connexion anonyme, typique
            // pour un accès en lecture seule.
            echo 'Le résultat de connexion depuis ' . $_SERVER['SERVER_ADDR'] . ' est ' . $r . '<br />';

            echo 'Recherchons (uid=m*) ...';
            // Recherche par nom de famille
            $sr=ldap_search($ds, "ou=people, dc=univ-fcomte, dc=fr", "uid=m*");
            echo 'Le résultat de la recherche est ' . $sr . '<br />';

            echo 'Le nombre d\'entrées retournées est ' . ldap_count_entries($ds,$sr)
                . '<br />';

            echo 'Lecture des entrées ...<br />';
            $info = ldap_get_entries($ds, $sr);
            echo 'Données pour ' . $info["count"] . ' entrées:<br />';
            echo "<ul>";
            for ($i=0; $i<$info["count"]; $i++) {
                echo "<li>" . $info[$i]['mail'][0] ;
                echo "<ul>";
                foreach($info[$i] as $key => $value){
                    if(is_int($key)) continue;
                    echo "<li>" . $key . ' est : ';
                    echo "<ul>";
                    if(isset($value["count"])) {
                        for ($j = 0; $j < $value["count"]; $j++) {
                            echo "<li>" . $value[$j] . "</li>";
                        }
                    }else{
                        echo "<li>" . $value . "</li>";
                    }
                    echo "</ul>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</li>";
            }
            echo "</ul>";
            echo "</li>";

            echo 'Fermeture de la connexion';
            ldap_close($ds);

        } else {
            echo '<h4>Impossible de se connecter au serveur LDAP.</h4>';
        }

    }
}