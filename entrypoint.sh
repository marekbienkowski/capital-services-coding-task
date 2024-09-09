#!/bin/bash

# Create the JWT directory if it doesn't exist
mkdir -p /var/www/symfony/config/jwt

# Check if the private.pem file exists; if not, generate the keys
if [ ! -f /var/www/symfony/config/jwt/private.pem ]; then
    echo "Generating RSA keys..."
    openssl genpkey -algorithm RSA -out /var/www/symfony/config/jwt/private.pem -pkeyopt rsa_keygen_bits:4096
    openssl rsa -pubout -in /var/www/symfony/config/jwt/private.pem -out /var/www/symfony/config/jwt/public.pem
    chmod 600 /var/www/symfony/config/jwt/private.pem
    chmod 644 /var/www/symfony/config/jwt/public.pem
else
    echo "RSA keys already exist."
fi

# Execute the container's main process (the one defined in the Dockerfile's CMD)
exec "$@"
