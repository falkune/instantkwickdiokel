# une base de données avec tois tables servant à enregistrer les messages et d'autre informations sur les utilisateurs
# les fonctionnalités de l'application sont :
   1 => la creation de compte en choisissant un pseudo et un mot de passe
   2 => la connexion à l'application avec les identifiant de creation du compte
   3 => envoi de message à un autre
   4 => deconnexion 
#contraintes:
	1 => il ne peut pas avoir deux pseudonymes identiques dans la base de données
	2 => une fois connecter l'utilisateur peut voir ses diferentes discution avec les autres
# structure de la base de données :
   1 => la table Users : les infos d'identification des users
      	1-1 => ID : identifiant de l'utilisateur unique en auto increment 
      	1-2 => Login : le pseudo de l'utilisateur
	1-3 => Email : mail de l'user
      	1-4 => Password : le mot de passe de l'utilisateur
   2 => la table Postedmessages : les messages que s'envoient les user
      	2-1 =>  ID : numero du message unique en auto increment
      	2-2 => Message : le texte du message
      	2-3 => From : l'identifiant de l'auteur du message
	2-4 => To : l'identifiant du destinatère du message
   3 => la table Connexion : historique de connexion des users
      	1 => ID : identifiant de la connexion
      	2 => Date_debut : date de debut de la connexion (timestamp)
      	3 => Date_fin : date de fin de la connexion (timestamp)
      	4 => Token : une valeur atribuer à un user actif 
      	5 => IP : adresse ip de l'user
	6 => User: identifiant de luser connecté
      