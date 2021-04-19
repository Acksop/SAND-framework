# language: fr
Fonctionnalité: Calculer l'âge d'une personne
    En tant qu'utilisateur de l'application
    Je veux connaître le nombre d'années écoulées entre deux dates
    De telle sorte que je puisse connaître mon age

Scénario: Calculer l'âge d'une personne depuis une date antérieure à aujourd'hui
    Etant donné que je suis né le 06/07/1986
    Et que nous sommes le 20/09/013
    Quand je calcule mon âge
    Alors je suis informé que j'ai 27 ans

Scénario: Calculer l'âge d'une personne depuis une date postérieure à aujourd'hui
    Etant donné que je suis né le 06/07/3013
    Et que nous sommes le 20/09/2013
    Quand je calcule mon âge
    Alors je suis informé que je ne suis pas encore né

Scénario: Calculer l'âge d'une personne dont c'est l'anniversaire aujourd'hui
    Etant donné que je suis né le 06/07/1986
    Et que nous sommes le 06/07/2013
    Quand je calcule mon âge
    Alors je suis informé que j'ai 27 ans
    Et on me souhaite un joyeux anniversaire