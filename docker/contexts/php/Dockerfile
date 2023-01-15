ARG TLR_IMG
FROM ${TLR_IMG}
MAINTAINER Paweł Tomulik <pawel@tomulik.pl>

ARG TLR_USER='tailor'
ARG TLR_GROUP='tailor'
ARG TLR_UID=6789
ARG TLR_GID=6789
ARG TLR_HOME=/home/${TLR_USER}
ARG TLR_CODE=/code

ENV TLR_USER=$TLR_USER \
    TLR_GROUP=$TLR_GROUP \
    TLR_UID=$TLR_UID \
    TLR_GID=$TLR_GID \
    TLR_HOME=$TLR_HOME \
    TLR_CODE=$TLR_CODE

# extensions needed by our project
RUN if [ -f /etc/alpine-release ]; then \
        apk update && \
        apk add --no-cache libzip-dev zip autoconf build-base; \
    else \
        apt-get update && \
        apt-get install -y libzip-dev zip autoconf build-essential; \
        apt-get clean; \
    fi; \
    docker-php-ext-install zip; \
    pecl install --force pcov && pecl run-tests pcov; \
    docker-php-ext-enable pcov; \
    if [ -f /etc/alpine-release ]; then \
        apk del autoconf build-base; \
        rm -rf /apk /tmp/* /var/cache/apk/*; \
    else \
        apt-get autoremove --purge autoconf build-essential; \
        rm -rf /var/lib/apt/lists/*; \
    fi

RUN if [ -f /etc/alpine-release ]; then \
      addgroup -g $TLR_GID $TLR_GROUP && \
      adduser -h $TLR_HOME -G $TLR_GROUP -u $TLR_UID -D $TLR_USER; \
    else \
      groupadd -g $TLR_GID $TLR_GROUP && \
      useradd -m -d $TLR_HOME -g $TLR_GROUP --uid $TLR_UID $TLR_USER; \
    fi; \
    mkdir -p $TLR_CODE && chown $TLR_USER:$TLR_GROUP $TLR_CODE

VOLUME $TLR_CODE

WORKDIR $TLR_CODE

# vim: set ft=dockerfile:
