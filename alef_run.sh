#!/usr/bin/env bash
DIR="$(pwd)"
echo "0 3 * * * /usr/bin/php ${DIR}/cron/clean.php > /dev/null &" | crontab
echo "0 6 * * * /usr/bin/php ${DIR}/cron/check-delivery.php > /dev/null &" | crontab
