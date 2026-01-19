FROM php:8.1-apache

WORKDIR /var/www/html

# Copy application
COPY . /var/www/html

# Enable Apache rewrite
RUN a2enmod rewrite

# Add start script to allow Apache to listen on $PORT
COPY docker-start.sh /usr/local/bin/docker-start.sh
RUN chmod +x /usr/local/bin/docker-start.sh

# Ensure proper ownership
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["/usr/local/bin/docker-start.sh"]
