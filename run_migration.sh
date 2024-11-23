#!/bin/bash

# Exit on error
set -e

# Define variables
PROJECT_DIR=$(pwd)
DOCKER_PHP_CONTAINER="symfony_php"

# Navigate to the project directory
cd "$PROJECT_DIR"

# Print current status
echo "Running migrations for Symfony project in $PROJECT_DIR"

# Check if running inside Docker
if docker ps | grep -q "$DOCKER_PHP_CONTAINER"; then
    echo "Detected Docker container: $DOCKER_PHP_CONTAINER"
    echo "Executing migrations inside Docker..."
    docker exec -it "$DOCKER_PHP_CONTAINER" php bin/console cache:clear --no-interaction
    docker exec -it "$DOCKER_PHP_CONTAINER" php bin/console doctrine:cache:clear-metadata --no-interaction
    docker exec -it "$DOCKER_PHP_CONTAINER" php bin/console doctrine:schema:update --force --no-interaction
    docker exec -it "$DOCKER_PHP_CONTAINER" php bin/console doctrine:migrations:migrate --no-interaction
else
    echo "Executing migrations locally..."
    php bin/console doctrine:migrations:migrate --no-interaction
fi

# Check if the migration was successful
if [ $? -eq 0 ]; then
    echo "Migrations executed successfully!"
else
    echo "Migration failed. Check logs for details."
    exit 1
fi
