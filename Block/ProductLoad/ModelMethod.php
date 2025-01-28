<?php
/**
 * Copyright © 2025 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\Module\Block\ProductLoad;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;

class ModelMethod extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Product
     */
    private $product;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Product $product
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Product $product,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->product = $product;
    }

    /**
     * Get product by ID using Model Method
     *
     * @param int $productId
     * @return Product
     * @throws LocalizedException
     */
    public function getProductById($productId)
    {
        try {
            $product = $this->product->load($productId);
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
