<?php
/**
 * Copyright © 2025 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\Module\Block\ProductLoad;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

class SearchCriteriaMethod extends \Magento\Framework\View\Element\Template
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Constructor function
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productRepository = $productRepository;
    }

    /**
     * Get product by ID using SearchCriteria
     *
     * @param int $productId
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws LocalizedException
     */
    public function getProductById($productId)
    {
        try {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('entity_id', $productId, 'eq')
                ->create();

            $products = $this->productRepository->getList($searchCriteria)->getItems();

            if (empty($products)) {
                throw new LocalizedException(__('Product not found'));
            }

            return reset($products);
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Error searching for product: %1', $e->getMessage())
            );
        }
    }
}
