<?php

/**
 * Inspiration https://github.com/cmall/LocalHomePage
 */

$configs = [
    ['name' => 'nginx', 'required' => true],
    ['name' => 'mariadb', 'required' => true],
    ['name' => 'mailhog', 'required' => true],
    ['name' => 'minio', 'required' => true],
];

foreach ($configs as $config) {
    $configFile = sprintf('config/%s.config.php', $config['name']);

    $fileExists = file_exists($configFile);
    if ($config['required'] && !$fileExists) {
        die(sprintf('Le fichier de config "%s" est manquant', $configFile));
    }

    $fileExists && require_once $configFile;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>mac-playbook</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background: url('background.png');
        }
        #main-content {
            padding-top: 10px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">🏠 mac-playbook</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="phpinfo.php" target="_blank">phpinfo()</a></li>
            <li class="nav-item"><a class="nav-link" href="http://<?php echo $mailhogUi; ?>" target="_blank">Mailhog</a></li>
            <?php if(isset($rabbitmqManagementUrl)): ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo $rabbitmqManagementUrl ?>" target="_blank">RabbitMQ</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div >
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="https://github.com/TheGrowingPlant/mac-playbook" target="_blank">Github</a></li>
        </ul>
    </div>
</nav>

<div id="main-content" class="container-fluid">
    <div class="row">

        <div class="col-6">
            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2>PHP</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Log</th>
                        <td class="w-50"><?php echo ini_get('error_log'); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2>MariaDB</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">DSN</th>
                        <td class="w-50"><?php echo sprintf('mysql://%s:%s@%s:%s', $mariadbUser, $mariadbPassword, $mariadbHost, $mariadbPort); ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Log</th>
                        <td class="w-50"><?php echo $mariadbLogPath; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2>Mailhog</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Interface web</th>
                        <td class="w-50"><a href="http://<?php echo $mailhogUi; ?>" target="_blank">Ouvrir</a></td>
                    </tr>
                    <tr>
                        <th class="w-50">Serveur SMTP</th>
                        <td class="w-50"><?php echo $mailhogSmtp; ?></td>
                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2>Minio</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Interface Web</th>
                        <td class="w-50"><a href="http://127.0.0.1:<?php echo $minioPort; ?>" target="_blank">Ouvrir</a></td>
                    </tr>
                    <tr>
                        <th class="w-50">Port</th>
                        <td class="w-50"><?php echo $minioPort; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Access Key</th>
                        <td class="w-50"><?php echo $minioAccessKey; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Secret Key</th>
                        <td class="w-50"><?php echo $minioSecretKey; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-6">
            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2>Nginx</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Log</th>
                        <td class="w-50"><?php echo $webServerLogPath; ?></td>
                    </tr>
                    </tbody>
                </table>

                <h3>Sites</h3>

                <table class="table table-striped table-bordered">
                    <thead>
                        <th class="w-50">Nom</th>
                        <th class="w-50">Chemin</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($sites as $site) {
                        echo <<<HTML
                        <tr>
                            <th><a target="_blank" href="{$site['url']}">{$site['name']}</a></th>
                            <td>{$site['path']}</td>
                        </tr>
HTML;
                    }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
