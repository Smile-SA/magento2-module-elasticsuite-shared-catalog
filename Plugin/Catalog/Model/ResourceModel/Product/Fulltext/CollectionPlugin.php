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

namespace Smile\ElasticsuiteSharedCatalog\Plugin\Catalog\Model\ResourceModel\Product\Fulltext;

use Smile\ElasticsuiteCatalog\Model\ResourceModel\Product\Fulltext\Collection as ProductCollection;
use Magento\Store\Model\ScopeInterface;

/**
 * Fulltext product collection plugin.
 *
 * @category Smile
 * @package  Smile\ElasticsuiteSharedCatalog
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class CollectionPlugin
{
    /**
     * @var \Magento\Company\Model\CompanyContext
     */
    private $companyContext;

    /**
     * @var \Magento\SharedCatalog\Model\Config
     */
    private $config;

    /**
     * @var \Magento\SharedCatalog\Model\CustomerGroupManagement
     */
    private $customerGroupManagement;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Constructor for ProductCollectionSetVisibility class.
     *
     * @param \Magento\Company\Model\CompanyContext $companyContext
     * @param \Magento\SharedCatalog\Model\Config $config
     * @param \Magento\SharedCatalog\Model\CustomerGroupManagement $customerGroupManagement
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Company\Model\CompanyContext $companyContext,
        \Magento\SharedCatalog\Model\Config $config,
        \Magento\SharedCatalog\Model\CustomerGroupManagement $customerGroupManagement,
        \Magento\Store\Model\StoreManagerInterface $storeManager

    ) {
        $this->companyContext          = $companyContext;
        $this->config                  = $config;
        $this->customerGroupManagement = $customerGroupManagement;
        $this->storeManager            = $storeManager;
    }

    public function beforeGetSize(ProductCollection $collection)
    {
        $this->applySharedCatalogFilter($collection);

        return [];
    }

    public function beforeLoad(ProductCollection $collection, $printQuery = false, $logQuery = false)
    {
        $this->applySharedCatalogFilter($collection);

        return [$printQuery, $logQuery];
    }

    private function applySharedCatalogFilter(ProductCollection $collection)
    {
        $customerGroupId = $this->companyContext->getCustomerGroupId();

        if ($this->isActive($customerGroupId) && !$collection->isLoaded()) {
            $collection->addFieldToFilter("customer_group_id", $customerGroupId);
        }

        return $this;
    }

    private function isActive($customerGroupId)
    {
        $websiteId                = $this->storeManager->getWebsite()->getId();
        $isActive                 = $this->config->isActive(ScopeInterface::SCOPE_WEBSITE, $websiteId);
        $isMasterCatalogAvailable = $this->customerGroupManagement->isMasterCatalogAvailable($customerGroupId);

        return $isActive && !$isMasterCatalogAvailable;
    }
}
