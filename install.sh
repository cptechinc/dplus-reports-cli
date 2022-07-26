# Install php modules
yum install php-xml php-apcu -y

# Copy Apcu ini files
/bin/cp apcu.ini /etc/php.d/40-apcu.ini