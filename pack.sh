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
rm -rf vendor/bolt/bolt/app/extensions/HelloWorld
rm -rf vendor/bolt/bolt/app/extensions/SimpleForms
rm -rf vendor/bolt/bolt/app/extensions/PlusOne
rm -rf vendor/bolt/bolt/app/extensions/Editable
rm -rf vendor/bolt/bolt/app/extensions/PasswordProtect
rm -rf vendor/bolt/bolt/app/extensions/TwitterFeed
rm -rf vendor/bolt/bolt/app/extensions/TwitterButton
rm -rf vendor/bolt/bolt/app/extensions/Disqus
rm -rf vendor/bolt/bolt/app/extensions/Authenticate
rm -rf vendor/bolt/bolt/app/extensions/NiceUrls
rm -rf vendor/bolt/bolt/app/extensions/RelatedContentByTags
rm -rf vendor/bolt/bolt/app/extensions/JSONAccess
rm -rf vendor/bolt/bolt/app/extensions/GoogleAnalytics
rm -rf vendor/bolt/bolt/app/extensions/RSSFeed
rm -rf vendor/bolt/bolt/app/extensions/MetaTags
rm -rf vendor/bolt/bolt/app/extensions/ImportWXR
rm -rf vendor/bolt/bolt/app/extensions/PinIt
rm -rf vendor/bolt/bolt/app/extensions/WaffleOrders
rm -rf vendor/bolt/bolt/app/extensions/RSSAggregator
rm -rf vendor/bolt/bolt/app/extensions/FacebookComments
rm -rf vendor/bolt/bolt/app/extensions/RateIt
rm -rf vendor/bolt/bolt/app/extensions/FacebookLike
rm -rf vendor/bolt/bolt/app/extensions/MenuEditor
rm -rf vendor/bolt/bolt/app/extensions/Redirector
rm -rf vendor/bolt/bolt/app/extensions/Socialite
rm -rf vendor/bolt/bolt/app/extensions/TitleCase
rm -rf vendor/bolt/bolt/app/extensions/TestSnippets
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
if [ -h web/kcfinder ]; then rm web/kcfinder; fi
if [ -d vendor/richardhinkamp/kcfinder ]; then mv vendor/richardhinkamp/kcfinder web/kcfinder; fi
rm -rf web/files/
rm -rf web/upload/
rm -rf web/fancybox/demo
rm -rf web/fancybox/lib/jquery*
rm -rf web/fancybox/source/jquery.fancybox.js
rm -rf web/tmp/
rm -rf .idea
rm -f config/config_local.yml
rm -f pack.sh
rm -f composer.json
rm -f composer.lock
mkdir -p cache
