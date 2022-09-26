# API BileMilo

## Installation
git@github.com:marinejourdan/Bilemilo.git
Le contenu sera téléchargé dans le dossier où vous vous situez
Paramêtrer le.env.dist avec vos propres données.
Démarrage du serveur: ``symfony server:start``
Chargement des fixtures:``php bin/console doctrine:fixtures:load``

## Ressources:

### connexion :
https://127.0.0.1:8000/api/login_check (POST)

Body test: 
```
{
    "username": "alapointe@gmail.com",
    "password": "password"
}
```

Récupération du token: envoi des informations de connexion via le body
Récupération du token et le copier dans le header :Authorization (choisir option Bearer).

### Acces liste téléphones
Liste des téléphones: https://127.0.0.1:8000/api/phones (GET)

### Acces détail téléphone
Détail d'un téléphone: https://127.0.0.1:8000/api/phones/{id} (GET)

### Acces liste clients
Liste des clients: https://127.0.0.1:8000/api/clients (GET)

### Acces détail client
détail d'un client: https://127.0.0.1:8000/api/clients\{id} (GET)

### Acces modification client
Modification d'un client: https://127.0.0.1:8000/api/clients\{id} (PUT)

### Création d'un client
Modification d'un client: https://127.0.0.1:8000/api/clients (POST)


Lien vers la documentation :
https://127.0.0.1:8000/api/doc