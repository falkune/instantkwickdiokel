# une base de données avec deux tables servant à enregistrer les messages des mem bres du forum
# les fonctionnalités de l'application sont :
   1 => la creation de compte en choisissant un pseudo et un mot de passe
   2 => la connexion à l'application avec les identifiant de creation du compte
   3 => poster un message dans la discution
   4 => deconnexion 
# il ne peut pas avoir trois pseudonymes identiques dans la base de données
# une fois connecter l'utilisateur peut voir le fil de la discution
# structure de la base de données :
   1 => la table Users :
      1-1 => ID : identifiant de l'utilisateur unique en auto increment 
      1-2 => Login : le pseudo de l'utilisateur
      1-3 => Password : le mot de passe de l'utilisateur
   2 => la table Postedmessages :
      1 =>  ID : numero du message unique en auto increment
      2 => Message : le texte du message
      3 => From : l'identifiant de l'auteur du message
   3 => la table Connexion :
      1 => ID : identifiant de la connexion
      2 => Date_debut : date de debut de la connexion (timestamp)
      3 => Date_fin : date de fin de la connexion (timestamp)
      4 => Token : une valeur atribuer à un user actif 
      5 => IPaddress : l'addresse ip
      6 => Browser : le navigateur utiliser
      