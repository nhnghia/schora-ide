############################################################
# Dockerfile to build MMT container images
# Based on Ubuntu
############################################################

FROM ubuntu:14.04

MAINTAINER Huu-Nghia

RUN apt-get update 
RUN apt-get install -y openjdk-7-jre-headless
RUN apt-get install -y apache2 

RUN apt-get install -y php5 libapache2-mod-php5 

# Enable apache mods
RUN a2enmod php5


ADD schora-apache.conf /etc/apache2/sites-enabled/
RUN rm -r /var/www/html/*
ADD ide /var/www/html/
RUN chown -R www-data:www-data /var/www/html

RUN chmod +x /var/www/html/php/soft/z3/bin/z3

EXPOSE 80

# By default, simply start apache.
CMD /usr/sbin/apache2ctl -D FOREGROUND

#ENTRYPOINT ["/bin/bash"]
