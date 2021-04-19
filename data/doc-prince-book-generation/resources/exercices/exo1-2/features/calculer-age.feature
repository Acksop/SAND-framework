# language: fr
Fonctionnalité: Calculer l'âge d'une personne
    En tant qu'utilisateur de l'application
    Je veux connaître le nombre d'années écoulées entre deux dates
    De telle sorte que je puisse connaître mon age

    Plan du Scénario: Calculer l'âge d'une personne
      Etant donné que je suis né le "<dateNaissance>"
      Et que nous sommes le "<dateDuJour>"
      Quand je calcule mon âge
      Alors on me répond "<reponseAttendu>"

      Exemples:
        | dateNaissance | dateDuJour | reponseAttendu                           |
        | 06/07/1986    | 20/09/2013 | Vous avez 27 ans                         |
        | 06/07/1985    | 20/09/2013 | Vous avez 28 ans                         |
        | 26/11/2020    | 20/09/2013 | Vous n'êtes pas encore né                |
        | 06/07/1986    | 06/07/2013 | Vous avez 27 ans. Joyeux anniversaire    |