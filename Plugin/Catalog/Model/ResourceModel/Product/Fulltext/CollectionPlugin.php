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
     * @var boolean[]
     */
    private $sharedCatalogStatusByGroup = [];

    /**
     * Constructor.
     *
     * @param \Magento\Company\Model\CompanyContext                $companyContext          Company context.
     * @param \Magento\SharedCatalog\Model\Config                  $config                  Shared catalog config.
     * @param \Magento\SharedCatalog\Model\CustomerGroupManagement $customerGroupManagement Customer group manager.
     * @param \Magento\Store\Model\StoreManagerInterface           $storeManager            Store manager.
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

    /**
     * Apply shared calatog filter catalog before getting collection size.
     *
     * @param ProductCollection $collection Product collection.
     *
     * @return array
     */
    public function beforeGetSize(ProductCollection $collection)
    {
        $this->applySharedCatalogFilter($collection);

        return [];
    }

    /**
     * Apply shared calatog filter catalog before loading collection.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param ProductCollection $collection Product collection.
     * @param boolean           $printQuery Print the query.
     * @param boolean           $logQuery   Log the query.
     *
     * @return array
     */
    public function beforeLoad(ProductCollection $collection, $printQuery = false, $logQuery = false)
    {
        $this->applySharedCatalogFilter($collection);

        return [$printQuery, $logQuery];
    }

    /**
     * Apply shared calatog filter catalog to the collection.
     *
     * @param ProductCollection $collection Product collection.
     *
     * @return $this
     */
    private function applySharedCatalogFilter(ProductCollection $collection)
    {
        $customerGroupId = $this->companyContext->getCustomerGroupId();

        if (!$collection->isLoaded() && $this->isActive($customerGroupId)) {
            $collection->addFieldToFilter("customer_group_id", $customerGroupId);
        }

        return $this;
    }

    /**
     * Check if the shared catalog feature is active for the customer group id.
     *
     * @param int $customerGroupId Customer group id.
     *
     * @return boolean
     */
    private function isActive($customerGroupId)
    {
        if (!isset($this->sharedCatalogStatusByGroup[$customerGroupId])) {
            $websiteId                = $this->storeManager->getWebsite()->getId();
            $isActive                 = $this->config->isActive(ScopeInterface::SCOPE_WEBSITE, $websiteId);
            $isMasterCatalogAvailable = $this->customerGroupManagement->isMasterCatalogAvailable($customerGroupId);

            $this->sharedCatalogStatusByGroup[$customerGroupId] = $isActive && !$isMasterCatalogAvailable;
        }

        return $this->sharedCatalogStatusByGroup[$customerGroupId];
    }
}
