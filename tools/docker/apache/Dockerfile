FROM php:7.2-apache

COPY ./tools/apache/default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

CMD ["start-apache"]
