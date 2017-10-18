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

namespace Smile\ElasticsuiteSharedCatalog\Model\Product\Indexer\Fulltext\Datasource;

use Smile\ElasticsuiteCore\Api\Index\DatasourceInterface;
use Smile\ElasticsuiteSharedCatalog\Model\ResourceModel\Product\Indexer\Fulltext\Datasource\SharedCatalog as ResourceModel;
use Smile\ElasticsuiteSharedCatalog\Helper\Data as SharedCatalogHelper;

/**
 * Datasource used to index shared catalogs customer group ids.
 *
 * @category Smile
 * @package  Smile\ElasticsuiteSharedCatalog
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class SharedCatalog implements DatasourceInterface
{
    /**
     * @var SharedCatalogHelper
     */
    private $helper;

    /**
     * @var ResourceModel
     */
    private $resourceModel;

    /**
     * Constructor.
     *
     * @param SharedCatalogHelper $helper        ElasticSuite Shared Catalog helper.
     * @param ResourceModel       $resourceModel Resource model.
     */
    public function __construct(SharedCatalogHelper $helper, ResourceModel $resourceModel)
    {
        $this->helper        = $helper;
        $this->resourceModel = $resourceModel;
    }

    /**
     * {@inheritdoc}
     */
    public function addData($storeId, array $indexData)
    {
        if ($this->helper->isEnabled()) {
            $customerGroupsData = $this->resourceModel->loadCustomerGroupsData(array_keys($indexData));
            foreach ($customerGroupsData as $row) {
                $productId       = (int) $row['entity_id'];
                $customerGroupId = (int) $row['customer_group_id'];
                $indexData[$productId]['customer_group_id'][] = $customerGroupId;
            }
        }

        return $indexData;
    }
}
