FROM php:8.2-apache

# Installer les utilitaires nécessaires et activer mod_rewrite
RUN apt-get update && apt-get install -y --no-install-recommends \
    vim \
    git \
    curl \
    && a2enmod rewrite \
    && service apache2 restart

# Définir le ServerName pour Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# Copier les fichiers de l'application
ADD . /var/www/ 

# Exposer le port 80
EXPOSE 80

# Commande par défaut pour démarrer Apache
CMD ["apache2-foreground"]