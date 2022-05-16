#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use App\Command\AddTask;
use App\Command\DeleteTasks;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new DeleteTasks());
$application->add(new AddTask());

$application->run();