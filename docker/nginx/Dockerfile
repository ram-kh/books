FROM nginx:1.25-alpine

RUN apk add --no-cache curl

COPY ./nginx/snippets /etc/nginx/snippets
COPY ./nginx/conf.d /etc/nginx/conf.d

WORKDIR /app

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s CMD curl --fail http://127.0.0.1/health || exit 1