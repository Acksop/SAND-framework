#language: fr
Fonctionnalité: Gérer un compte bancaire
  Afin de gérer mon compte bancaire
  En tant qu'utilisateur connecté
  Je peux ajouter ou retirer de l'argent sur mon compte
 
  Contexte:
    Etant donné que je suis connecté en tant que "jean-françois"
    Et que j'ai "50" euro
    Et je suis sur "/"
 
  Scénario: Consulter mes comptes
    Alors je devrais voir "Vous avez 50 euro sur votre compte"
 
  Plan du Scénario: Ajouter de l'argent
    Etant donné que j'ai "<montantInitial>" euro
    Quand je sélectionne "<operation>" depuis "Operation"
    Et je remplis "Montant" avec "<montant>"
    Et je presse "Go"
    Alors je devrais voir "Vous avez <montantFinal> euro sur votre compte"
 
    Exemples:
     | operation     | montantInitial| montant   | montantFinal  |
     | Ajouter       | 50            | 10        | 60            |
     | Ajouter       | 50            | 20        | 70            |
     | Ajouter       | 50            | 5         | 55            |
     | Ajouter       | 50            | 0         | 50            |
     | Retirer       | 50            | 10        | 40            |
     | Retirer       | 50            | 20        | 30            |
     | Retirer       | 50            | 30        | 20            |
 
  Scénario: Les découverts sont interdits
    Etant donné que j'ai "50" euro
    Quand je sélectionne "Retirer" depuis "Operation"
    Et je remplis "Montant" avec "60"
    Et je presse "Go"
    Alors je devrais voir "Vous avez 50 euro sur votre compte"
    Et je devrais voir "Les decouverts ne sont pas autorises"