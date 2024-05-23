FROM php:8.2-apache

#Mise à jour du système
RUN apt-get update && apt-get upgrade -y && apt-get install -y

#Installation d'extension par Docker et activation
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# Copie du contenu de l'application dans le répertoire de travail
COPY . /public

# Définir le répertoire de travail
#WORKDIR /public

#On initialise le port
EXPOSE 80


#Problème d'installation de mysqli → Fais un docker-compose build avant
