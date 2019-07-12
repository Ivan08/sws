FROM yiisoftware/yii2-php:7.3-apache

RUN docker-php-ext-install sockets

RUN apt-get update && apt-get install -y supervisor
RUN mkdir -p /var/log/supervisor
COPY ./queue.conf /etc/supervisor/conf.d/queue.conf

COPY ./wrapper.sh /etc/wrapper.sh
RUN chmod +x /etc/wrapper.sh

CMD ["/etc/wrapper.sh"]
