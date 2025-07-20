#!/bin/bash

# Migrate jika perlu (opsional)
# php artisan migrate --force

# Jalankan Laravel dari public folder
php -S 0.0.0.0:$PORT -t public
