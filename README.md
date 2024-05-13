# Vide Grenier en Ligne

### Mode Gitflow

Ce document montre toutes les marches à suivres sur le projet vide grenier en ligne

# 1: Création des issues

- A chaque bugs trouvé sur le site WEB ou autre tâches du projet une issue devra impérativement être créé.
- Un titre court et clair doit être donné à chaque issue avec si besoin une description pour donner plus d'informations.
- 2 labels sont à mettre pour chaque issue le premier permet de connaitre le statut de l'issue (en cours, à faire, recette, finis) ainsi 
qu'un label pour le degrés d'importance (secondaire, important, urgent).
- Chaque développpeur doit avoir seulement 1 issue active pour ne pas s'éparpiller et travailler dans de meilleures condition. Si vous avez des difficultés sur l'issue assigné faite monter l'information à votre groupe.


# 2: Branchs

- Chaque issue possède ça branch qui sera ensuite supprimé lors de la merge request.
- La branch Main sera la branch de production, la Stage sera la branch de pré-production enfin, la branch Develop sera utilisé pour faire les merges request au fur et à mesure.


# 3: Merge request 

- Les merges request doivent être faites à chaque fin d'issue. (la branch develop est par défault il ne faut pas changer)
- La merge request doit avoir le nom de l'issue qu'elle corrige.
- Ne surtout pas déselectionner la case "delete branch source"
- Vous pouvez maintenant créer votre merge request qui devra être tester et approuvé par un autre developpeur
- Le développeur qui testera la merge request doit aller sur la branch et tester le code. Il doit ensuite appuyé sur approve ce qui permettra de connaitre l'identité de la personne qui à approuvé. Après avoir fait ça et vérifié que la branch cible était la bonne vous pouvez merge.



### Mise en place du projet back-end

1. Créez un VirtualHost pointant vers le dossier /public du site web (Apache)
2. Importez la base de données MySQL (sql/import.sql)
3. Connectez le projet et la base de données via les fichiers de configuration
4. Lancez la commande `composer install` pour les dépendances

## Mise en place du projet front-end
1. Lancez la commande `npm install` pour installer node-sass
2. Lancez la commande `npm run watch` pour compiler les fichiers SCSS

## Routing

Le [Router](Core/Router.php) traduit les URLs. 

Les routes sont ajoutées via la méthode `add`. 

En plus des **controllers** et **actions**, vous pouvez spécifier un paramètre comme pour la route suivante:

```php
$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);
```


## Vues

Les vues sont rendues grâce à **Twig**. 
Vous les retrouverez dans le dossier `App/Views`. 

```php
View::renderTemplate('Home/index.html', [
    'name'    => 'Toto',
    'colours' => ['rouge', 'bleu', 'vert']
]);
```
## Models

Les modèles sont utilisés pour récupérer ou stocker des données dans l'application. Les modèles héritent de `Core
\Model
` et utilisent [PDO](http://php.net/manual/en/book.pdo.php) pour l'accès à la base de données. 

```php
$db = static::getDB();
```
