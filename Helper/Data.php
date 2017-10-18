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

namespace Smile\ElasticsuiteSharedCatalog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * ElasticSuite Shared Catalog Helper
 *
 * @category Smile
 * @package  Smile\ElasticsuiteSharedCatalog
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context       Context.
     * @param \Magento\Framework\Module\Manager     $moduleManager Module manager.
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Module\Manager $moduleManager
    ) {
        parent::__construct($context);
        $this->moduleManager = $moduleManager;
    }

    /**
     * Check if the Magento_SharedCatalog is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->moduleManager->isEnabled("Magento_SharedCatalog");
    }
}
