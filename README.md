# Simplified POS Management for Magento 2 orders

Manage orders per POS on Magento 2.
* Screencast (5.5Mb): http://www.screencast.com/t/Xb0xHmxR16l 
* (if screencast issue then use other browser)

## Author

This free and open source Magento 2 extension is brought to you by [Ocheretnyi](https://www.linkedin.com/in/iocheretnyi) - A quality focused web developer.

## Requirements:

* Magento 2.0.0 Stable or higher

## Features

* Supports POS assign for order(s) from backend at order grid via UI components
* Notify POS support by email with order content
* Respects Magento 2 standards
* Supports Magento 2.0.0 and up

### Instructions for manual copy
These are the steps:
* Upload the files in the `source/` folder to the folder `app/code/Ocheretnyi/Pos` of your site
* Run `php -f bin/magento module:enable Ocheretnyi_Pos`
* Run `php -f bin/magento setup:upgrade`
* Flush the Magento cache
* Done
