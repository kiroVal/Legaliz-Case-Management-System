# Use official PHP with Apache
FROM php:8.1-apache

# Enable PHP extensions for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy project files into container
COPY . /var/www/html

# Expose Apache web server port
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]