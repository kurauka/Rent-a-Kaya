#!/bin/bash
set -e

# Default port if not provided by Render
PORT=${PORT:-8080}

# Update Apache to listen on the requested port
sed -ri "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80/:${PORT}/g" /etc/apache2/sites-available/*.conf

exec apache2-foreground
