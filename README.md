# Magento 2 Custom Module

   - Composer version: ``vendor/module-module``
   - Module name: ``Vendor_Module``

## Main Functionalities
- Provides Magento 2 practical tutorials.

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Vendor`
 - Enable the module by running `php bin/magento module:enable Vendor_Module`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Features

1. Different ways to get product data by id in magento 2.
   - Repository Pattern Method
   - Factory Pattern Method
   - Object Manager Method
   - Model Method
   - Resource Model Method
   - SearchCriteria Method
   - Collection Method
