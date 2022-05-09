<?php
define('ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('SAVE_COMPOSE_PATH', dirname(ROOT) . DIRECTORY_SEPARATOR . 'docker-compose.yml');
define('COMPOSE_TEMPLATE_PATH', ROOT . 'docker-compose-template.yml');
define('BUILD_MODE_LOCAL', 'local');
define('BUILD_MODE_PROD', 'prod');
define('VAR_FORMATTING', '#$(%s)');
define('BUILD_MODES', [
    BUILD_MODE_PROD,
    BUILD_MODE_LOCAL,
]);
define('REQUIRED_FLAGS', [
   'mode',
]);
define('REQUIRED_FLAGS_VALIDATION', [
    'mode' => BUILD_MODES,
]);
require_once 'defines.php';

/**
 * FUNCTIONS
 */
$criticalFunction = function ($message) {
    echo $message . "\n";
    exit;
};
$successfulExitFunction = function ($message) {
    echo $message . "\n";
    exit;
};

/**
 * FLAGS
 */
$prepareArgsFunction = function (array $args)
{
    $preparedArgs = [];
    unset($args[0]);
    foreach ($args as $arg) {
        list($argK, $argV) = explode('=', $arg);
        $argK = substr($argK, 2);
        $preparedArgs[$argK] = $argV;
    }
    return $preparedArgs;
};
$ensureArgumentsPresent = function (array $current) use ($criticalFunction)
{
    $diff = array_diff(array_keys($current), REQUIRED_FLAGS);
    if (!empty($diff)) {
        $criticalFunction('The following flags should be passed: ' . join($diff));
    }

    foreach (REQUIRED_FLAGS_VALIDATION as $key => $values) {
        if (!in_array($current[$key], $values)) {
            $criticalFunction("Argument {$key} is invalid");
        }
    }

};
$getArgVFunction = static function ($key, $def = null) use ($prepareArgsFunction, $argv, $ensureArgumentsPresent) {
    static $preparedArgs;
    if (!empty($preparedArgs)) {
        return isset($preparedArgs[$key]) ? $preparedArgs[$key] : $def;
    }

    $preparedArgs = $prepareArgsFunction($argv);
    $ensureArgumentsPresent($preparedArgs);
    return isset($preparedArgs[$key]) ? $preparedArgs[$key] : $def;
};

/**
 * BUILD MODE
 */
$buildMode = $getArgVFunction('mode');

/**
 * VARS
 */
$vars = TEMPLATE_VARS;
$prepareVarsFunction = function (array $vars)
{
    $prepared = [];
    foreach ($vars as $key => $val) {
        $key = sprintf(VAR_FORMATTING, $key);
        $prepared[$key] = $val;
    }
    return $prepared;
};
$vars = $prepareVarsFunction($vars[$buildMode]);

/**
 * TEMPLATE
 */
$composeTemplate = file_get_contents(COMPOSE_TEMPLATE_PATH);
$template = strtr($composeTemplate, $vars);
file_put_contents(SAVE_COMPOSE_PATH, $template);

$successfulExitFunction("Docker-compose has been generated!");
