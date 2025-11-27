Installation et initialisation du projet :
Ce projet necessite l'installation préalable de php, composer, node.js et NPM.

1. Cloner le projet:
git clone https://github.com/acatry/runmates.git
cd runmates

2. Installer les dépendances:
composer install
npm install
npm run build

3. Création du fichier d'environnement:
cp .env.example .env

Le fichier env.example est déjà prédéfini sur:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=runmates
DB_USERNAME=root
DB_PASSWORD=

Créez maintenant la base de donnée runmates dans phpMyAdmin.

4. Un seed a été créée afin de faciliter l'aperçu de l'application.
Vous pouvez y avoir accès en lançant la commande:
php artisan migrate --seed 

5. Démarrage
php artisan serve

L'application sera accessible à l'adresse http://127.0.0.1:8000