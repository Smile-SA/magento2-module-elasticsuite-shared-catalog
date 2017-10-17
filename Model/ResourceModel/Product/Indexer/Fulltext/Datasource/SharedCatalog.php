<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\ElasticsuiteSharedCatalog
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\ElasticsuiteSharedCatalog\Model\ResourceModel\Product\Indexer\Fulltext\Datasource;

use Smile\ElasticsuiteCatalog\Model\ResourceModel\Eav\Indexer\Indexer;

/**
 * Datasource used to index shared catalogs customer group ids.
 *
 * @category Smile
 * @package  Smile\ElasticsuiteSharedCatalog
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class SharedCatalog extends Indexer
{
    public function loadCustomerGroupsData($productIds)
    {
        $select = $this->getConnection()->select()
          ->from(['e' => $this->getTable('catalog_product_entity')], ['entity_id'])
          ->join(['sc' => $this->getTable('shared_catalog_product_item')], "sc.sku = e.sku", ['customer_group_id'])
          ->where('e.entity_id IN(?)', $productIds)
          ->group(["sc.sku", "sc.customer_group_id"]);

        return $this->getConnection()->fetchAll($select);
    }
}
