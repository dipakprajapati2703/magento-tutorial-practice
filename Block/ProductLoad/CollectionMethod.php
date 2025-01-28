<?php
/**
 * Copyright © 2025 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\Module\Block\ProductLoad;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;

class CollectionMethod extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get product by ID using Collection Method
     *
     * @param int $productId
     * @return \Magento\Catalog\Model\Product
     * @throws LocalizedException
     */
    public function getProductById($productId)
    {
        try {
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('entity_id', $productId);
            $product = $collection->getFirstItem();

            if (!$product->getId()) {
                throw new LocalizedException(__('Product not found'));
            }

            return $product;
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Error loading product: %1', $e->getMessage())
            );
        }
    }
}
