#!/bin/bash

# Move to the Laravel app directory
cd /var/www/laravel-app || { echo "Error: Could not change directory to /var/www/laravel-app"; exit 1; }

# Fetch the latest changes from the remote repository
git fetch origin

# Checkout the 'dev' branch or create and track it
git checkout -B dev origin/dev

# Reset local changes to the latest commit on the dev branch
echo "Resetting local changes..."
git reset --hard origin/dev || { echo "Error: Git reset failed"; exit 1; }

# Install or update Laravel dependencies
echo "Installing or updating Laravel dependencies..."
composer install --optimize-autoloader || { echo "Error: Composer install failed"; exit 1; }

# Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan cache:clear || { echo "Error: Cache clear failed"; exit 1; }
php artisan config:clear || { echo "Error: Config clear failed"; exit 1; }
php artisan route:clear || { echo "Error: Route clear failed"; exit 1; }
php artisan view:clear || { echo "Error: View clear failed"; exit 1; }

# Optimize the class loader
echo "Optimizing the class loader..."
composer dump-autoload || { echo "Error: Composer dump-autoload failed"; exit 1; }

# Additional update steps if needed

# Success message
echo "Update successful."

# Exit with success status
exit 0
