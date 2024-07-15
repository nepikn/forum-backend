#

## 預期功能

-

## 主要技術

-

## 安裝

```bash
ssh DESTINATION
cd /var/www/html/
git clone git@github.com:nepikn/forum-backend.git

mkdir api
mv forum-backend/ api/forum/
cd api/forum/

sudo ln -s $(realpath .conf) /etc/apache2/conf-available/api-forum.conf
sudo a2enconf api-forum.conf
sudo systemctl restart apache2

cd db/
read -p "Enter the password of the MySQL user 'admin': " password
sed -i "s/auth_string/$password/g" init.sql conn.php
sudo mysql -u root < init.sql
sudo systemctl restart mysql
```

## 學習內容

###

```javascript

```

-

## 展望

## 相關資料

- []()

## 貢獻
