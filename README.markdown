# HOW TO

## USE

Just edit the pages in the pages/ folder. You can use markdown syntax and simple html tags.
The "page XX" text will be replaced by links automagically.

web/index.php contains most of the PHP glue
view/ contains the two twig templates

## INSTALL

Get composer, install, and make sure the cache/ folder is writeable.

    curl -s https://getcomposer.org/installer | php
    php composer.phar install

    sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx cache
    sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx cache