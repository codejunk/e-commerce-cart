<?php
/**
 * PHP Coding Standards Fixer configuration file
 * https://github.com/FriendsOfPHP/PHP-CS-Fixer
 *
 */

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
//        'strict_param' => true,
//        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;


/**
Place the following shell script into .git/hooks/pre-commit file
to run php-cs-fixer for every git commit automatically.
php-cs-fixer should be installed to /usr/local/bin/php-cs-fixer


#!/usr/bin/env bash
echo "php-cs-fixer pre commit hook start"

CURRENT_DIRECTORY=`pwd`
GIT_HOOKS_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIRECTORY="$GIT_HOOKS_DIR/../.."


PHP_CS_FIXER="/usr/local/bin/php-cs-fixer"
PHP_CS_CONFIG="app/.php_cs"
CHANGED_FILES=$(git diff --cached --name-only --diff-filter=ACM -- '*.php')

#echo $CHANGED_FILES
#echo $PROJECT_DIRECTORY

cd $PROJECT_DIRECTORY

if [ -n "$CHANGED_FILES" ]; then
    $PHP_CS_FIXER fix --config "$PHP_CS_CONFIG" $CHANGED_FILES;
    git add $CHANGED_FILES;
fi

echo "php-cs-fixer pre commit hook finish"


 */