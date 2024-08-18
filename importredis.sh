#!/bin/bash

# Database name
DB="DB11"

# Filename with data

FILENAME="/www/wwwroot/ips.test.b2.ink/data/IP2LOCATION-LITE-DB11.CSV"

# Redis connection details
REDIS_URL="redis://default:Xiaoye123\$j@localhost:6379"


# Delete old DB first
echo "DEL \"$DB\"" | redis-cli -u "$REDIS_URL" --verbose



cat $FILENAME | awk -vdb="$DB" 'BEGIN { FS="\",\""; } { $1 = substr($1, 2); $NF = substr($NF, 1, length($NF) - 1); printf "%s %s ", "ZADD", db; printf "%s \"%s|", $1, $1; for(i=3; i<=NF; i++) { if(i>3) { printf "%s", "|"; } printf "%s", $i; if(i==NF) { print ""; } } }' |  redis-cli -u "$REDIS_URL" -n 7 --pipe --pipe-timeout 0 --verbose