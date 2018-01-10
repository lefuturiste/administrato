<?php
chdir("{$argv[2]}");
shell_exec("git reset --hard");
shell_exec("git pull {$argv[1]}");
shell_exec('php composer.phar install');
shell_exec('php composer.phar update');
