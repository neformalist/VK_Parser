#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")
user=$(echo "$2")
password=$(echo "$3")
database=$(echo "$4")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Add PHp 7.1 repository"
add-apt-repository ppa:ondrej/php -y

info "Update OS software"
sudo locale-gen ru_RU
sudo locale-gen ru_RU.UTF-8
sudo update-locale 
apt-get update
apt-get upgrade -y

info "Install additional software"
apt-get install -y php7.1-curl php7.1-cli php7.1-intl php7.1-pgsql php7.1-gd php7.1-fpm php7.1-mbstring php7.1-xml unzip nginx postgresql postgresql postgresql-client


info "Configure PostgeSQL"
echo "-------------------- change LOCALE"
sudo pg_dropcluster --stop 9.5 main
sudo pg_createcluster --locale ru_RU.UTF-8 --start 9.5 main
echo "-------------------- fixing listen_addresses on postgresql.conf"
sudo sed -i "s/#listen_address.*/listen_addresses '*'/" /etc/postgresql/9.5/main/postgresql.conf
echo "-------------------- fixing postgres pg_hba.conf file"
# replace the ipv4 host line with the above line
sudo cat >> /etc/postgresql/9.5/main/pg_hba.conf <<EOF
# Accept all IPv4 connections - FOR DEVELOPMENT ONLY!!!
host    all         all         0.0.0.0/0             md5
EOF
echo "-------------------- creating postgres ${user} role with password ${password}"
# Create Role and login
sudo su postgres -c "psql -c \"CREATE ROLE ${user} SUPERUSER LOGIN PASSWORD '${password}'\" "
echo "-------------------- creating vagrant ${database}"
# Create WTM database
sudo su postgres -c "createdb -E UTF8 -T template0 --locale=ru_RU.utf8 -O ${user} ${database}"
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
