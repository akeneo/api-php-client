FROM debian:bullseye-slim

ARG DEV_UID=1000
ARG DEV_GID=1000

# Install some useful packages
RUN apt-get update && \
    apt-get --no-install-recommends --no-install-suggests --yes --quiet install \
        apt-transport-https \
        bash-completion \
        ca-certificates \
        curl \
        git \
        gnupg \
        imagemagick \
        less \
        make \
        perceptualdiff \
        procps \
        ssh-client \
        sudo \
        unzip \
        vim \
        wget && \
    apt-get clean && apt-get --yes --quiet autoremove --purge && \
    rm -rf  /var/lib/apt/lists/* /tmp/* /var/tmp/* \
            /usr/share/doc/* /usr/share/groff/* /usr/share/info/* /usr/share/linda/* \
            /usr/share/lintian/* /usr/share/locale/* /usr/share/man/*

# Install PHP with some extensions
RUN apt-get update && \
    apt-get --no-install-recommends --no-install-suggests --yes --quiet install \
        php7.4-cli \
        php7.4-apcu \
        php7.4-mbstring \
        php7.4-curl \
        php7.4-gd \
        php7.4-imagick \
        php7.4-intl \
        php7.4-bcmath \
        php7.4-xdebug \
        php7.4-xml \
        php7.4-zip && \
    apt-get clean && apt-get --yes --quiet autoremove --purge && \
    rm -rf  /var/lib/apt/lists/* /tmp/* /var/tmp/* \
            /usr/share/doc/* /usr/share/groff/* /usr/share/info/* /usr/share/linda/* \
            /usr/share/lintian/* /usr/share/locale/* /usr/share/man/* \

RUN usermod --uid ${DEV_UID} www-data && groupmod --gid ${DEV_GID} www-data &&

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

WORKDIR /home/docker/

ENV PATH=bin:vendor/bin:$PATH
