<?php
/**
 * Copyright � Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ConfigurableProduct\Plugin\Model\ResourceModel\Attribute;

use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\CatalogInventory\Model\ResourceModel\Stock\Status;
use Magento\ConfigurableProduct\Model\ResourceModel\Attribute\OptionSelectBuilderInterface;
use Magento\Framework\DB\Select;

/**
 * Plugin for OptionSelectBuilderInterface to add stock status filter.
 */
class InStockOptionSelectBuilder
{
    /**
     * CatalogInventory Stock Status Resource Model.
     *
     * @var Status
     */
    private $stockStatusResource;

    /**
     *
     * @var Configuration
     */
    private $stockConfiguration;

    /**
     * @param Status $stockStatusResource
     * @param StockConfigurationInterface $stockConfiguration
     */
    public function __construct(
        Status $stockStatusResource,
        StockConfigurationInterface $stockConfiguration
    ) {
        $this->stockStatusResource = $stockStatusResource;
        $this->stockConfiguration = $stockConfiguration;
    }

    /**
     * Add stock status filter to select.
     *
     * @param OptionSelectBuilderInterface $subject
     * @param Select $select
     * @return Select
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetSelect(OptionSelectBuilderInterface $subject, Select $select)
    {
        if (!$this->stockConfiguration->isShowOutOfStock()) {
            $select->joinInner(
                ['stock' => $this->stockStatusResource->getMainTable()], 'stock.product_id = entity.entity_id', []
            )->where(
                'stock.stock_status = ?', \Magento\CatalogInventory\Model\Stock\Status::STATUS_IN_STOCK
            );
        }
        return $select;
    }

}
