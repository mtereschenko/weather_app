# Base image declaration
FROM nginx:1.20.2-alpine AS base_image

# Development image declaration
FROM base_image AS development

COPY ./containers/nginx/config/nginx-dev.conf /etc/nginx/conf.d/default.conf
RUN sed -i -r "s/domain.localhost;/${PROJECT_NAME}.localhost;/g" /etc/nginx/conf.d/default.conf \
    && chmod 644 /etc/nginx/conf.d/default.conf
WORKDIR /app
