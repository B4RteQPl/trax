FROM nginx:1.19.5

# dev virtual host.
COPY docker/nginx/dev.tpl.conf /etc/nginx/conf.d/dev.tpl

# Compile virtual hosts.
CMD ["/bin/sh", "-c", "envsubst '${NGINX_DEV_HOST}' < /etc/nginx/conf.d/dev.tpl > /etc/nginx/conf.d/default.conf && nginx -g 'daemon off;'"]
