<?php
/**
 * Copyright © 2025 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\Module\Block\ProductLoad;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Exception\LocalizedException;

class FactoryMethod extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ProductFactory $productFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productFactory = $productFactory;
    }

    /**
     * Get product by ID using Factory Method
     *
     * @param int $productId
     * @return \Magento\Catalog\Model\Product
     * @throws LocalizedException
     */
    public function getProductById($productId)
    {
        try {
            $product = $this->productFactory->create()->load($productId);
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
