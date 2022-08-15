#!/bin/bash
source ./.env
mysql -uroot -p${MYSQL_PASSWORD} -e "use mysql;
update user set host='%' where user='root';
flush privileges;
grant all privileges on *.* to 'root'@'%';
flush privileges;"

