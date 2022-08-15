FROM mysql:8.0.29

COPY ./config/mysql/init.sh /mysql/init.sh
COPY ./.env /mysql/.env

CMD ["/bin/sh","/mysql/init.sh"]


