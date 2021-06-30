FROM nginx

RUN apt-get update && apt-get install -y \
    nano

RUN mkdir /etc/nginx/sites-enabled

ADD config/nginx/site.conf /etc/nginx/sites-available/site.conf

RUN ln -s /etc/nginx/sites-available/site.conf /etc/nginx/sites-enabled/site.conf