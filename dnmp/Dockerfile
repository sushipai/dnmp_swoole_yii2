ARG PHP_VERSION
FROM php:${PHP_VERSION}-fpm

ARG PHP_REDIS
ARG PHP_SWOOLE
ARG REPLACE_SOURCE_LIST

COPY ./sources.list /etc/apt/sources.list.tmp
RUN if [ "${REPLACE_SOURCE_LIST}" = "true" ]; then \
    mv /etc/apt/sources.list.tmp /etc/apt/sources.list; else \
    rm -rf /etc/apt/sources.list.tmp; fi

# Timezone
RUN /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo 'Asia/Shanghai' > /etc/timezone

# Install extensions from source
COPY ./extensions /tmp/extensions
RUN chmod +x /tmp/extensions/install.sh \
    && /tmp/extensions/install.sh \
    && rm -rf /tmp/extensions	
	
# Libs
RUN apt-get update \
    && apt-get install -y \
	git \
    curl \
    wget \
    git \
    zip \
    libz-dev \
    libssl-dev \
    libnghttp2-dev \
    libpcre3-dev \
    && apt-get clean \
    && apt-get autoremove
	
# 安装composer并允许root用户运行
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1
ENV COMPOSER_HOME=/usr/local/share/composer
RUN mkdir -p /usr/local/share/composer \
	&& curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
	&& chmod +x /tmp/composer-setup.php \
	&& php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot \
	&& rm -f /tmp/composer-setup.* \
    # 配置composer中国全量镜像
    # && composer config -g repo.packagist composer https://packagist.phpcomposer.com
	&& composer config -g repo.packagist composer https://packagist.laravel-china.org
	
# PDO extension
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli

# Bcmath extension
RUN docker-php-ext-install bcmath