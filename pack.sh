#!/usr/bin/env bash
rm -rf `find -name '.git' -type d`
rm -rf `find -name 'doc' -type d`
rm -rf `find -name 'docs' -type d`
rm -rf `find -name 'test' -type d`
rm -rf `find -name 'tests' -type d`
rm -rf `find -name 'Tests' -type d`
rm -rf `find -name 'Test' -type d | grep -v ./vendor/twig/twig/lib/Twig/Node/Expression/Test`
rm -rf `find -name 'cache' -type d | grep -v ./vendor/doctrine/cache`
rm -rf vendor/bolt/bolt/files
rm -rf vendor/bolt/bolt/theme
rm -rf vendor/bolt/bolt/app/view/css
rm -rf vendor/bolt/bolt/app/view/font
rm -rf vendor/bolt/bolt/app/view/img
rm -rf vendor/bolt/bolt/app/view/js
rm -rf vendor/bolt/bolt/app/view/lib
rm -rf vendor/bolt/bolt/app/classes/markdownify
rm -rf vendor/bolt/bolt/app/classes/upload
rm -rf vendor/twbs/bootstrap-sass
rm -rf vendor/symfony/icu/Symfony/Component/Icu/Resources/
find vendor/symfony/intl/Symfony/Component/Intl/Resources/data/ -name '*.json' ! -name en.json ! -name nl.json ! -name eu.json ! -name nl_NL.json -delete
rm -rf vendor/zend/gdata/demos/
rm -rf vendor/zend/gdata/documentation/
rm -rf vendor/zend/gdata/tests/
rm -rf vendor/filp/whoops/examples/
rm -rf vendor/hautelook/phpass/lib/
rm -rf vendor/twig/twig/ext/
rm -rf vendor/guzzle/guzzle/phing/
rm -rf vendor/swiftmailer/swiftmailer/notes/
rm -rf app/scss/
rm -rf .sass-cache
rm -rf web/files/
rm -rf web/upload/
rm -rf web/tmp/
rm -rf .idea
rm -f app/config/config_local.yml
rm -f pack.sh
rm -f .gitignore
mkdir -p app/cache
