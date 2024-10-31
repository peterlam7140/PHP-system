# Pull mysql image
FROM mysql

# environment
ENV MYSQL_ROOT_PASSWORD root

# Copy static assets into var/www
COPY ./dockerMysql/init /docker-entrypoint-initdb.d

# Expost port 3306
EXPOSE 3306