<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\ElasticsuiteSharedCatalog
 * @author    Pierre Gauthier <pierre.gauthier@smile.fr>
 * @copyright 2021 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\ElasticsuiteSharedCatalog\Model\Product\Search\Request\Container\Filter;

use Magento\Company\Model\CompanyContext;
use Magento\Framework\Exception\LocalizedException;
use Magento\SharedCatalog\Model\Config;
use Magento\SharedCatalog\Model\CustomerGroupManagement;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smile\ElasticsuiteCore\Api\Search\Request\Container\FilterInterface;
use Smile\ElasticsuiteCore\Search\Request\Query\QueryFactory;
use Smile\ElasticsuiteCore\Search\Request\QueryInterface;

/**
 * Product Customer Group Default filter.
 *
 * @category Smile
 * @package  Smile\ElasticsuiteSharedCatalog
 * @author   Pierre Gauthier <pierre.gauthier@smile.fr>
 */
class CustomerGroup implements FilterInterface
{
    /**
     * @var QueryFactory
     */
    private QueryFactory $queryFactory;

    /**
     * @var CompanyContext
     */
    private CompanyContext $companyContext;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var CustomerGroupManagement
     */
    private CustomerGroupManagement $customerGroupManagement;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var boolean[]
     */
    private array $sharedCatalogStatusByGroup = [];

    /**
     * Customer group filter constructor.
     *
     * @param QueryFactory            $queryFactory            Query Factory
     * @param CompanyContext          $companyContext          Company context
     * @param Config                  $config                  Shared catalog config.
     * @param CustomerGroupManagement $customerGroupManagement Customer group manager.
     * @param StoreManagerInterface   $storeManager            Store manager.
     */
    public function __construct(
        QueryFactory $queryFactory,
        CompanyContext $companyContext,
        Config $config,
        CustomerGroupManagement $customerGroupManagement,
        StoreManagerInterface $storeManager
    ) {
        $this->queryFactory            = $queryFactory;
        $this->companyContext          = $companyContext;
        $this->config                  = $config;
        $this->customerGroupManagement = $customerGroupManagement;
        $this->storeManager            = $storeManager;
    }

    /**
     * {@inheritdoc}
     * @throws LocalizedException
     */
    public function getFilterQuery()
    {
        $customerGroupId = $this->companyContext->getCustomerGroupId();
        if (!$this->isActive($customerGroupId)) {
            return null;
        }
        return $this->queryFactory->create(
            QueryInterface::TYPE_TERM,
            [
                'field' => 'customer_group_id',
                'value' => $customerGroupId,
            ]
        );
    }

    /**
     * Check if the shared catalog feature is active for the customer group id.
     *
     * @param int $customerGroupId Customer group id.
     *
     * @return boolean
     * @throws LocalizedException
     */
    private function isActive($customerGroupId)
    {
        if (!isset($this->sharedCatalogStatusByGroup[$customerGroupId])) {
            $websiteId                = $this->storeManager->getWebsite()->getId();
            $isActive                 = $this->config->isActive(ScopeInterface::SCOPE_WEBSITE, $websiteId);
            $isMasterCatalogAvailable = method_exists(CustomerGroupManagement::class, 'isPrimaryCatalogAvailable') ?
                $this->customerGroupManagement->isPrimaryCatalogAvailable($customerGroupId) :
                $this->customerGroupManagement->isMasterCatalogAvailable($customerGroupId);

            $this->sharedCatalogStatusByGroup[$customerGroupId] = $isActive && !$isMasterCatalogAvailable;
        }

        return $this->sharedCatalogStatusByGroup[$customerGroupId];
    }
}
