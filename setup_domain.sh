#!/usr/bin/env bash

set -e

CADDYFILE="Caddyfile"

# ------------------------
# validate_domain <domain>
# ------------------------
validate_domain() {
  local domain="$1"
  local DOMAIN_REGEX="^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$"

  if [[ ! $domain =~ $DOMAIN_REGEX ]]; then
    echo "❌ Invalid domain format"
    return 1
  fi

    #   if ! dig +short "$domain" A "$domain" AAAA | grep -q .; then
    #     echo "❌ Domain has no A/AAAA DNS record"
    #     return 1
    #   fi

  SERVER_IP=$(curl -4 https://ifconfig.me)
  DOMAIN_IP=$(dig +short "$domain" | head -n1)

  if [[ "$SERVER_IP" != "$DOMAIN_IP" ]]; then
    echo "❌ Домен не указывает на этот сервер"
    return 1
  fi
}

# --------------------------------
# write_localhost_caddyfile
# --------------------------------
write_localhost_caddyfile() {
  cat > "$CADDYFILE" <<EOF
# HTTPS on port 443
localhost {
    root * /var/www/html/web
    php_fastcgi app:9000
    file_server
    tls internal
}
EOF
}

# --------------------------------
# write_domain_caddyfile <domain>
# --------------------------------
write_domain_caddyfile() {
  local domain="$1"

  cat > "$CADDYFILE" <<EOF
# Domain: Caddy will use Let's Encrypt automatically (no tls line needed)
$domain {
    root * /var/www/html/web
    php_fastcgi app:9000
    file_server
}
EOF
}
