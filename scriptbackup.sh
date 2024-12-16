#!/bin/bash

WORDPRESS_DB_USER="wpuser"
WORDPRESS_DB_PASSWORD="wp1994"
WORDPRESS_DB_NAME="wordpress"
#MYSQL_DB_HOST=10.43.74.12
MYSQL_DB_HOST=$(kubectl get svc mysql -o jsonpath='{.spec.clusterIP}')

mysqldump --column-statistics=0 -u $WORDPRESS_DB_USER -p$WORDPRESS_DB_PASSWORD -h $MYSQL_DB_HOST $WORDPRESS_DB_NAME > databd.sql
