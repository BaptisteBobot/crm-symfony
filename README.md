# Projet Symfony 6.1
Ce projet est basé sur Symfony 6.1 et vise à fournir une base solide pour développer une application web moderne. Symfony est un framework PHP puissant et flexible qui permet de créer des applications web de haute qualité.

# Prérequis
Avant de pouvoir exécuter le projet, assurez-vous que votre système répond aux exigences suivantes :

PHP 7.4 ou version ultérieure
Composer (https://getcomposer.org/)
Extension Symfony requirements (https://symfony.com/doc/current/reference/requirements.html)
# Installation
## Clonez ce dépôt sur votre machine locale :
### bash
Copy code
git clone hhttps://github.com/BaptisteBobot/crm-symfony.git
cd projet-symfony-6.1
Installez les dépendances du projet à l'aide de Composer :
### bash
#### Copy code
composer install
### Configurez la base de données :
Symfony utilise Doctrine pour gérer les interactions avec la base de données. Assurez-vous d'avoir configuré correctement vos paramètres de base de données dans le fichier .env à la racine du projet.

Créez la base de données et exécutez les migrations :
### bash
#### Copy code
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
### Générez les clés SSH (pour les JWT tokens par exemple, si vous en utilisez) :
bash
Copy code
mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
### Lancez le serveur de développement :
bash
Copy code
symfony server:start
Le projet est maintenant accessible à l'adresse http://127.0.0.1:8000 dans votre navigateur.

Tests
Pour exécuter les tests du projet, utilisez la commande suivante :

bash
Copy code
php bin/phpunit
#### Documentation
Vous pouvez trouver la documentation officielle de Symfony sur le site Web de Symfony (https://symfony.com/doc/6.1/index.html). N'hésitez pas à vous y référer pour obtenir plus d'informations sur la structure du projet, les bonnes pratiques et les fonctionnalités du framework.







