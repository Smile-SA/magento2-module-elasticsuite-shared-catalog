## README

### What does the module ?

You should use this module in order to get ElasticSuite and Magento 2 B2B (Enterprise Edition only) features working together transparently.

It fix issues that can be encountered when using the Shared Catalog feature.

### How to install ?

Simply run these commands in your Magento install :

```
composer require smile/module-elasticsuite-shared-catalog
bin/magento setup:upgrade
```

Do not forget to clean caches and reindex everything !!!!

```
bin/magento cache:clean
bin/magento indexer:reindex
```

### What is ElasticSuite ?

Readme for the whole Smile ElasticSuite is available [here](https://github.com/Smile-SA/elasticsuite).

