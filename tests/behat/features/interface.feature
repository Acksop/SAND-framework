#language: fr
Fonctionnalité: Naviguer sur l'interface
  Afin de découvrir l'interface
  En tant qu'utilisateur anonymous
  Je peux naviguer sur l'interface
 
  Contexte:
    Etant donné que je suis connecté en tant que "anonymous"
    Et je suis sur "/"
 
  Scénario: Consulter la documentation
    Quand je clique sur "Documentation"
    Alors je devrais voir "Sommaire"
 
  Scénario: Consulter le dépot Git
    Quand je clique sur "Dépot"
    Alors je devrais voir "GitList"

  Scénario: Consulter la page donation
    Quand je clique sur "Donate"
    Alors je devrais voir "Become a Sponsor !"

  Scénario: Consulter les CGU
    Quand je clique sur "CGU Terms"
    Alors je devrais voir "Conditions Générale de l'application:"

  Scénario: Consulter la Politique de Confidentilité
    Quand je clique sur "Policy"
    Alors je devrais voir "Politique Générale de Sécurité"