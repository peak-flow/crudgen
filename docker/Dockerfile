FROM centos:centos7

RUN yum -y install deltarpm
RUN yum -y update && \
    yum -y install \
    epel-release \
    httpd \
    httpd-tools \
    unzip ksh zip unzip \
    yum-utils nano which wget epel-release  httpd-devel  \
    && yum groupinstall -y 'Development tools'

RUN yum -y install mod_proxy_uwsgi mod_proxy_html libssh2-devel

RUN yum install -y https://rpms.remirepo.net/enterprise/remi-release-7.rpm

RUN yum install -y --enablerepo=remi-php81 php php-mcrypt php-cli php-gd php-curl php-mysql \
    php-ldap php-zip php-fileinfo  php-xdebug  php-pear php-devel php-sockets php-mbstring \
    php81-php-oci8 php81-php-devel php81-php-pecl-ssh2 php-pecl-ssh2 mysql

#composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


#prepare other installs
ARG FOLDERNAME=installtemp
ARG PHP_VERSION=8.1
ARG PHP_VERSION_SHORT=81
RUN mkdir -p /$FOLDERNAME
RUN chmod 777 -R /$FOLDERNAME

#sql server
RUN yum install -y --enablerepo=remi-php81  unixODBC-devel
RUN pecl install sqlsrv pdo_sqlsrv

RUN curl https://packages.microsoft.com/config/rhel/7/prod.repo > /etc/yum.repos.d/mssql-release.repo
RUN ACCEPT_EULA=Y yum install -y msodbcsql17
RUN ACCEPT_EULA=Y yum install -y mssql-tools
RUN echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
RUN source ~/.bashrc

RUN echo extension=pdo_sqlsrv.so >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/30-pdo_sqlsrv.ini
RUN echo extension=sqlsrv.so >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/20-sqlsrv.ini

#db2
RUN mkdir -p /$FOLDERNAME/opt/ibm
COPY docker/linuxx64_odbc_cli.tar.gz $FOLDERNAME/opt/ibm/
COPY docker/ibm_data_server_driver_package_linuxx64_v11.5.tar.gz /$FOLDERNAME/opt/ibm/
WORKDIR /$FOLDERNAME/opt/ibm
RUN tar xzf ibm_data_server_driver_package_linuxx64_v11.5.tar.gz -C /$FOLDERNAME/opt/ibm
WORKDIR /$FOLDERNAME/opt/ibm/dsdriver

RUN chmod 755 installDSDriver
RUN ksh installDSDriver
RUN export IBM_DB_HOME=/$FOLDERNAME/opt/ibm/dsdriver && \
    env IBM_DB_HOME=/$FOLDERNAME/opt/ibm/dsdriver && \
    pecl install ibm_db2 && \
    echo "extension=ibm_db2.so" >> /etc/php.d/ibm_db2.ini

RUN touch /var/log/xdebug.log
RUN /bin/bash -c "mkdir -p /var/log/php"
RUN touch /var/log/php/mono.log
RUN touch /var/log/php/error.log
RUN chmod -R +rwX  /var/log/
RUN chown -R apache:apache /var/log/
RUN chown -R apache:apache /var/www/html

RUN echo ' log_errors = on '  >> /etc/php.ini
RUN echo ' error_log = /var/log/php/error.log '  >> /etc/php.ini
RUN echo ' xdebug.mode=develop,coverage,debug,profile '  >> /etc/php.ini
RUN echo ' xdebug.idekey=docker '  >> /etc/php.ini
RUN echo ' xdebug.discover_client_host=true '  >> /etc/php.ini
RUN echo ' xdebug.log=/var/log/xdebug.log '  >> /etc/php.ini
RUN echo ' xdebug.log_level=10 '  >> /etc/php.ini
RUN echo ' xdebug.client_host=host.docker.internal '  >> /etc/php.ini
RUN echo ' xdebug.client_port=9003 '  >> /etc/php.ini
RUN echo ' xdebug.start_with_request=yes '  >> /etc/php.ini
RUN echo ' xdebug.var_display_max_data=512 '  >> /etc/php.ini
RUN echo ' xdebug.var_display_max_depth=3 '  >> /etc/php.ini
RUN echo ' xdebug.var_display_max_children=128 '  >> /etc/php.ini
RUN echo ' xdebug.cli_color=1 '  >> /etc/php.ini
RUN echo ' xdebug.show_local_vars=0 '  >> /etc/php.ini
RUN echo ' xdebug.dump_globals=true '  >> /etc/php.ini
RUN echo ' xdebug.dump_once=true '  >> /etc/php.ini
RUN echo ' xdebug.dump_undefined=false; '  >> /etc/php.ini
RUN echo ' xdebug.dump.SERVER=REMOTE_ADDR,REQUEST_METHOD '  >> /etc/php.ini
RUN echo ' xdebug.dump.GET=*  '  >> /etc/php.ini
RUN echo ' xdebug.dump.POST=*  '  >> /etc/php.ini
RUN echo ' xdebug.max_stack_frames=-1  '  >> /etc/php.ini
RUN echo ' xdebug.show_exception_trace=0 '  >> /etc/php.ini

# ORACLE oci
RUN mkdir /opt/oracle \
    && cd /opt/oracle

ADD https://download.oracle.com/otn_software/linux/instantclient/199000/instantclient-basic-linux.x64-19.9.0.0.0dbru.zip /opt/oracle
ADD https://download.oracle.com/otn_software/linux/instantclient/199000/instantclient-sdk-linux.x64-19.9.0.0.0dbru.zip /opt/oracle

# Install Oracle Instantclient
RUN  unzip /opt/oracle/instantclient-basic-linux.x64-19.9.0.0.0dbru.zip -d /opt/oracle \
    && unzip /opt/oracle/instantclient-sdk-linux.x64-19.9.0.0.0dbru.zip -d /opt/oracle \
    && ln -s /opt/oracle/instantclient_19_9/libclntshcore.so.19.9 /opt/oracle/instantclient_19_9/libclntshcore.so \
    && rm -rf /opt/oracle/*.zip

ENV LD_LIBRARY_PATH  /opt/oracle/instantclient_19_9:${LD_LIBRARY_PATH}

RUN yum install -y yum install systemtap-sdt-devel libaio \
	&& export PHP_DTRACE=yes \
	&& wget https://www.php.net/distributions/php-8.1.16.tar.gz

	RUN  tar xvzf php-8.1.16.tar.gz \
	&& cd php-8.1.16/ext/pdo_oci \
	&& phpize \
	&& ./configure --with-pdo-oci=instantclient,/opt/oracle/instantclient_19_9/ \
	&& make && make install

RUN  mkdir -p /opt/ibm/dsdriver/ \
	&& cd /opt/ibm/dsdriver/ \
	&&  pear download pecl/oci8-3.0.1 \
	&& tar xvzf oci8-3.0.1.tgz \
	&& cd oci8-3.0.1 \
	&& export PHP_DTRACE=yes \
	&& phpize \
	&& ./configure --with-oci8=instantclient,/opt/oracle/instantclient_19_9/ \
	&& make && make install

COPY ./docker/php/30-pdo_oci.ini /etc/php.d/30-pdo_oci.ini
COPY ./docker/php/20-oci8.ini /etc/php.d/20-oci8.ini

RUN echo 'alias vi="vim"' >> /root/.bashrc
RUN echo 'alias ll="ls -la"' >> /root/.bashrc
RUN echo 'alias cdp="cd /var/www/html"' >> /root/.bashrc
RUN echo 'alias cdml="cd /var/log"' >> /root/.bashrc
RUN echo 'alias cdl="cd /var/www/html/storage/logs"' >> /root/.bashrc
RUN echo 'alias log="tail -f /var/www/html/storage/logs/laravel.log"' >> /root/.bashrc
RUN echo 'alias qlog="tail -f /var/www/html/storage/logs/queues.log"' >> /root/.bashrc
RUN echo 'alias pbcopy="xclip -selection clipboard"' >> /root/.bashrc
RUN echo 'alias pbpaste="xclip -selection clipboard -o"' >> /root/.bashrc
RUN echo 'alias phpinfo="echo  \"<?php phpinfo(); \" | php "' >> /root/.bashrc
RUN echo ' alias logt="tail -fn 0 /var/www/html/storage/logs/twilio/queues$(date +%Y-%m-%d).csv" ' >> /root/.bashrc
RUN echo 'alias reload="source ~/.bashrc"' >> /root/.bashrc
RUN echo 'alias dump="composer dumpautoload"' >> /root/.bashrc
RUN echo 'alias gs="git status"' >> /root/.bashrc
RUN echo 'alias gst="git status"' >> /root/.bashrc
RUN echo 'alias las="ls -latr"' >> /root/.bashrc
RUN echo 'alias phpunit="vendor/bin/phpunit --color"' >> /root/.bashrc
RUN echo 'alias punit="vendor/bin/phpunit --color"' >> /root/.bashrc
RUN echo 'alias punitc="vendor/bin/phpunit --color"' >> /root/.bashrc

RUN yum install -y supervisor beanstalkd iproute xclip zsh net-tools

#added systemctl alternative
RUN wget https://raw.githubusercontent.com/gdraheim/docker-systemctl-replacement/master/files/docker/systemctl.py -O /usr/local/bin/systemctl
RUN chmod 755 /usr/local/bin/systemctl

# Update Apache Configuration
ENV HTTPD_MAIN_CONF_PATH=/etc/httpd/conf \
	HTTPD_DATA_ORIG_PATH=/var/www \
	HTTPD_MAIN_CONF_D_PATH=/etc/httpd/conf.d \
	APP_DATA=${APP_ROOT}/src  \
	HTTPD_VAR_PATH=/var \
	APACHE_LOG_DIR=/var/log/httpd \
	SLIM_MODE=testing \
    	CI_ENV=testing \
    	SOME_KEY=someValue

RUN mkdir -p /etc/httpd/sites-enabled
RUN mkdir -p /etc/httpd/sites-available

RUN chown -R apache:apache /var/www/html

COPY docker/httpd.conf /etc/httpd/conf/httpd.conf
COPY docker/apache.conf /etc/httpd/sites-enabled/000-default.conf
COPY docker/apache.conf /etc/httpd/sites-available/000-default.conf

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start-container /usr/local/bin/start-container
COPY docker/php.ini /etc/php/8.0/cli/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

#ENV PHP_INI_SCAN_DIR=/usr/local/etc/php/conf.d

RUN mkdir -p /var/www/html/storage/logs/
RUN chmod -R +rwX  /var/www/html/storage/logs/
RUN chown -R apache:apache /var/www/html/storage/logs/
RUN chmod -R 777 /var/www/html/storage/logs/



WORKDIR "/var/www/html"


CMD ["/bin/bash","start-container"]
