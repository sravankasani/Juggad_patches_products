Jugaad Patches 

Introduction:

Jugaad patches products drupal module which will create content type
to store the products details and block to provide the purchse link
for buying the products

Requirements:

- Install codeitnowin/barcode package from packagist.org using: composer
  require codeitnowin/barcode.

Installation:

- Install the Jugaad Patches module as you would normally install a
contributed Drupal module. Visit https://www.drupal.org/node/1897420 
for further information.
- Add below snippet in your main composer.json file and run the
composer require drupal/juggad_products_module:dev-main to download
the module and it's dependencies.
"repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:sravankasani/Juggad_patches_products"
        }
    ], 
