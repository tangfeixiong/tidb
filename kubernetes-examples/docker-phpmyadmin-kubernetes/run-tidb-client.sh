#!/bin/sh
set -x

eval Q8SAD_HOST='$'${Q8SAD_ENV}_SERVICE_HOST
eval Q8SAD_PORT='$'${Q8SAD_ENV}_SERVICE_PORT
PMA_HOST=${PMA_HOST:-`echo $Q8SAD_HOST`}
PMA_PORT=${PMA_PORT:-`echo $Q8SAD_PORT`}
PMA_USER=${PMA_USER:-root}
export PMA_HOST
export PMA_PORT
export PMA_USER

if [ ! -f /www/config.secret.inc.php ] ; then
    cat > /www/config.secret.inc.php <<EOT
<?php
\$cfg['blowfish_secret'] = '`cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1`';
EOT
fi

exec env PMA_HOST=$PMA_HOST PMA_PORT=$PMA_PORT PMA_USER=$PMA_USER php -S 0.0.0.0:80 -t /www/ \
    -d upload_max_filesize=$PHP_UPLOAD_MAX_FILESIZE \
    -d post_max_size=$PHP_UPLOAD_MAX_FILESIZE \
    -d max_input_vars=$PHP_MAX_INPUT_VARS \
    -d session.save_path=/sessions
