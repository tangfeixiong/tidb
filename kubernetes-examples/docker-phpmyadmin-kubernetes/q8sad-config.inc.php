<?php

require('./config.secret.inc.php');

/* Ensure we got the environment */
$vars = array(
    'PMA_ARBITRARY',
    'PMA_HOST',
    'PMA_HOSTS',
    'PMA_PORT',
    'PMA_USER',
    'PMA_PASSWORD',
);
$k8sEnvVars = array(
    'PXC_CLUSTER_SERVICE_HOST','PXC_CLUSTER_SERVICE_PORT','MYSQL_ROOT_PASSWORD',
);
foreach ($vars as $var) {
    if (!isset($_ENV[$var]) && getenv($var)) {
        $_ENV[$var] = getenv($var);
    }
}
foreach ($k8sEnvVars as $k8sEnvVal) {
    if (!isset($_ENV[$k8sEnvVal]) && getenv($k8sEnvVal)) {
        $_ENV[$k8sEnvVal] = getenv($k8sEnvVal);
    }
}

/* Arbitrary server connection */
if (isset($_ENV['PMA_ARBITRARY']) && $_ENV['PMA_ARBITRARY'] === '1') {
    $cfg['AllowArbitraryServer'] = true;
}

/* Figure out hosts */

/* Fallback to default linked */
$hosts = array('db');

/* Set by environment */
if (!empty($_ENV['PMA_HOST'])) {
    $hosts = array($_ENV['PMA_HOST']);
} elseif (!empty($_ENV['PMA_HOSTS'])) {
    $hosts = explode(',', $_ENV['PMA_HOSTS']);
}

/* Server settings */
for ($i = 1; isset($hosts[$i - 1]); $i++) {
    $cfg['Servers'][$i]['host'] = $hosts[$i - 1];
    if (isset($_ENV['PMA_PORT'])) {
        $cfg['Servers'][$i]['port'] = $_ENV['PMA_PORT'];
    }
    if (isset($_ENV['PMA_USER'])) {
        $cfg['Servers'][$i]['auth_type'] = 'config';
        $cfg['Servers'][$i]['user'] = $_ENV['PMA_USER'];
        $cfg['Servers'][$i]['password'] = isset($_ENV['PMA_PASSWORD']) ? $_ENV['PMA_PASSWORD'] : null;
    } else {
        $cfg['Servers'][$i]['auth_type'] = 'cookie';
    }
    $cfg['Servers'][$i]['connect_type'] = 'tcp';
    $cfg['Servers'][$i]['compress'] = false;
    $cfg['Servers'][$i]['AllowNoPassword'] = true;
}

if (isset($_ENV['PXC_CLUSTER_SERVICE_HOST'])) {
    $i = count($cfg['Servers']);
    $cfg['Servers'][$i]['host'] = $_ENV['PXC_CLUSTER_SERVICE_HOST'];
    if (isset($_ENV['PXC_CLUSTER_SERVICE_PORT'])) {
        $cfg['Servers'][$i]['port'] = $_ENV['PXC_CLUSTER_SERVICE_PORT'];
    }
    if (isset($_ENV['MYSQL_ROOT_PASSWORD'])) {
        $cfg['Servers'][$i]['auth_type'] = 'config';
        $cfg['Servers'][$i]['user'] = 'root';
        $cfg['Servers'][$i]['password'] = $_ENV['MYSQL_ROOT_PASSWORD'];
    } else {
        $cfg['Servers'][$i]['auth_type'] = 'cookie';
    }
    $cfg['Servers'][$i]['connect_type'] = 'tcp';
    $cfg['Servers'][$i]['compress'] = false;
    $cfg['Servers'][$i]['AllowNoPassword'] = true;
}

/* Uploads setup */
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';
