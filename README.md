## README

## What does the module do ?

You should use this module in order to get ElasticSuite and Magento 2 B2B (Enterprise Edition only) features working together transparently.

It fixes issues that can be encountered when using the Shared Catalog feature.

### Which version should I use ?

**The module version patterns are identical to those of Elasticsuite.**

Magento Version                   | Magento B2B Version | ElasticSuite Version         | Module version | Module composer install                                                 | Supported Elasticsearch Version | Actively maintained
----------------------------------|---------------------|------------------------------|----------------|-------------------------------------------------------------------------|---------------------------------|---------------------
Magento **2.2.x** Commerce (EE)   | **1.0.x**           | ElasticSuite **2.6.x**       | **2.6.x**      | ```composer require smile/module-elasticsuite-shared-catalog ~2.6.0```  | 5.x & 6.x                       | No
Magento **<2.3.5** Commerce (EE)  | **1.1.x**           | ElasticSuite **2.8.x**       | **2.8.x**      | ```composer require smile/module-elasticsuite-shared-catalog ~2.8.0```  | 5.x & 6.x                       | No
Magento **>=2.3.5** Commerce (EE) | **1.1.x**           | ElasticSuite **2.9.x**       | **2.9.x**      | ```composer require smile/module-elasticsuite-shared-catalog ~2.9.0```  | 6.x & 7.x                       | **Yes**
Magento **>=2.4.1** Commerce (EE) | **1.3.x**           | ElasticSuite **2.10.x**      | **2.10.x**     | ```composer require smile/module-elasticsuite-shared-catalog ~2.10.0``` | 6.x & 7.x                       | **Yes**

### How to install ?

Simply run these commands in your Magento install :

```
composer require smile/module-elasticsuite-shared-catalog ~2.9.0
bin/magento setup:upgrade
```

Do not forget to clean caches and reindex everything !!!!

```
bin/magento cache:clean
bin/magento indexer:reindex
```

### What is ElasticSuite ?

Readme for the whole Smile ElasticSuite is available [here](https://github.com/Smile-SA/elasticsuite).

