#!/bin/sh
set -e

# Railway: paste Aiven CA PEM into AIVEN_CA_PEM (multi-line env var).
if [ -n "${AIVEN_CA_PEM:-}" ]; then
	printf '%s\n' "$AIVEN_CA_PEM" > /tmp/aiven-ca.pem
	export MYSQL_ATTR_SSL_CA=/tmp/aiven-ca.pem
fi

exec php -S "0.0.0.0:${PORT:-8080}" -t public public/index.php
