<?php
/**
 * Copyright © 2025 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\Module\Block\ProductLoad;

use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;

class ResourceModelMethod extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ProductResource
     */
    private $productResource;

    /**
     * @var Product
     */
    private $product;

    /**
     * Constructor function
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param \Magento\Catalog\Model\Product $product
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ProductResource $productResource,
        Product $product,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productResource = $productResource;
        $this->product = $product;
    }

    /**
     * Get product by ID using Resource Model
     *
     * @param int $productId
     * @return Product
     * @throws LocalizedException
     */
    public function getProductById($productId)
    {
        try {
            $product = $this->product;
            $this->productResource->load($product, $productId);
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
