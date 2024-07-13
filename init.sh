mv forum-backend/ /var/www/html/api/forum/
cd /var/www/html/api/forum/

sudo ln -fs api.conf /etc/apache2/conf-available/api.conf
sudo a2enconf api.conf
sudo systemctl restart apache2

cd db/
read -s -p "Enter the password of the MySQL user 'admin': " password
sed -i "s/auth_string/$password/g" init.sql conn.php
sudo mysql -u root < init.sql
sudo systemctl restart mysql
