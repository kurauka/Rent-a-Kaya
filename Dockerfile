
FROM php:8.1-apache

WORKDIR /var/www/html

# Copy application
COPY . /var/www/html

# Install system deps and PHP MySQL extensions (mysqli, pdo_mysql)
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
	   default-mysql-client \
	   default-libmysqlclient-dev \
	   libzip-dev \
	   zlib1g-dev \
	   libxml2-dev \
	&& docker-php-ext-install mysqli pdo pdo_mysql \
	&& rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Add start script to allow Apache to listen on $PORT
COPY docker-start.sh /usr/local/bin/docker-start.sh
RUN chmod +x /usr/local/bin/docker-start.sh

# Ensure proper ownership
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["/usr/local/bin/docker-start.sh"]
