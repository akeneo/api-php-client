FROM debian:bullseye-slim

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

# Install PHP 8.2 with some extensions
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    sh -c 'echo "deb https://packages.sury.org/php/ bullseye main" > /etc/apt/sources.list.d/php.list' && \
    apt-get update && \
    apt-get --no-install-recommends --no-install-suggests --yes --quiet install \
        php8.2-cli \
        php8.2-apcu \
        php8.2-mbstring \
        php8.2-curl \
        php8.2-gd \
        php8.2-imagick \
        php8.2-intl \
        php8.2-bcmath \
        php8.2-xdebug \
        php8.2-xml \
        php8.2-zip && \
    apt-get clean && apt-get --yes --quiet autoremove --purge && \
    rm -rf  /var/lib/apt/lists/* /tmp/* /var/tmp/* \
            /usr/share/doc/* /usr/share/groff/* /usr/share/info/* /usr/share/linda/* \
            /usr/share/lintian/* /usr/share/locale/* /usr/share/man/*

# Add a "docker" user
RUN useradd docker --shell /bin/bash --create-home \
  && usermod --append --groups sudo docker \
  && echo 'ALL ALL = (ALL) NOPASSWD: ALL' >> /etc/sudoers \
  && echo 'docker:secret' | chpasswd

WORKDIR /home/docker/

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

ENV PATH=bin:vendor/bin:$PATH
