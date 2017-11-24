FROM mojdigital/wordpress-base:latest

ADD . /bedrock

WORKDIR /bedrock

ARG COMPOSER_USER
ARG COMPOSER_PASS

RUN make clean && \
    make create-composer-auth && \
    make build && \
    make remove-composer-auth
