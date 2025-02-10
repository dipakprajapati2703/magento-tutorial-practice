<?php
namespace Vendor\Module\Block\OrderLoad;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\OrderFactory;

class FetchOrderData extends \Magento\Framework\View\Element\Template
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        OrderRepositoryInterface $orderRepository,
        OrderFactory $orderFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->orderRepository = $orderRepository;
        $this->orderFactory = $orderFactory;
    }

    /**
     * Get product by ID using Repository Pattern
     *
     * @param int $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws NoSuchEntityException
     */
    public function getOrderById($orderId)
    {
        try {
            return $this->orderRepository->get($orderId);
        } catch (NoSuchEntityException $e) {
            throw new NoSuchEntityException(
                __('The order with ID "%1" does not exist.', $orderId)
            );
        }
    }

    /**
     * Get product by ID using Factory Method
     *
     * @param int $orderIncrementId
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws LocalizedException
     */
    public function getOrderByIdUsingFactory($orderIncrementId)
    {
        try {
            $order = $this->orderFactory->create()->loadByIncrementId($orderIncrementId);
            if (!$order->getId()) {
                throw new LocalizedException(__('Order not found'));
            }
            return $order;
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Error loading product: %1', $e->getMessage())
            );
        }
    }

    /**
     * Get Order Information function
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderInformation($order)
    {
        $orderData = [];
        $orderData = [
            "order_id" => $order->getEntityId(),
            "increment_id" => $order->getIncrementId(),
            "customer_email" => $order->getCustomerEmail(),
            "order_state" => $order->getState(),
            "order_status" => $order->getStatus(),
            "order_total" => $order->getGrandTotal(),
            "order_subtotal" => $order->getSubtotal(),
            "total_qty_ordered" => $order->getTotalQtyOrdered(),
            "order_currency" => $order->getOrderCurrencyCode(),
            "store_id" => $order->getStoreId(),
            "remote_ip" => $order->getRemoteIp()
        ];
        return $orderData;
    }

    /**
     * Get Order Shipping Information function
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderShippingAddress($order)
    {
        $orderShippingAddress = [];
        /* check order is not virtual */
        if (!$order->getIsVirtual()) {
            $shippingAddress = $order->getShippingAddress();
            $street = $shippingAddress->getStreet();
            $orderShippingAddress = [
                "firstname" => $shippingAddress->getFirstname(),
                "lastname" => $shippingAddress->getLastname(),
                "street_1" => isset($street[0])? $street[0]:"",
                "street_2" => isset($street[1])? $street[1]:"",
                "city" => $shippingAddress->getCity(),
                "region_id" => $shippingAddress->getRegionId(),
                "region" => $shippingAddress->getRegion(),
                "postcode" => $shippingAddress->getPostcode(),
                "country_id" => $shippingAddress->getCountryId(),
            ];
        }
        return $orderShippingAddress;
    }

    /**
     * Get Order Billing Information function
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderBillingAddress($order)
    {
        $orderBillingAddress = [];
        $billingAddress = $order->getBillingAddress();
        $street = $billingAddress->getStreet();
        $orderBillingAddress = [
            "firstname" => $billingAddress->getFirstname(),
            "lastname" => $billingAddress->getLastname(),
            "street_1" => isset($street[0])? $street[0]:"",
            "street_2" => isset($street[1])? $street[1]:"",
            "city" => $billingAddress->getCity(),
            "region_id" => $billingAddress->getRegionId(),
            "region" => $billingAddress->getRegion(),
            "postcode" => $billingAddress->getPostcode(),
            "country_id" => $billingAddress->getCountryId(),
        ];
        return $orderBillingAddress;
    }

    /**
     * Get Customer Information function
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getCustomerData($order)
    {
        $customerId= $order->getCustomerId();
        $customerEmail = $order->getCustomerEmail();

        $customerData = [
            "guestCustomer" => $order->getCustomerIsGuest(),
            "customer_id" => $customerId,
            "email" => $customerEmail,
            "groupId " => $order->getCustomerGroupId(),
            "firstname" => $order->getCustomerFirstname(),
            "middlename" => $order->getCustomerMiddlename(),
            "lastname" => $order->getCustomerLastname(),
            "prefix" => $order->getCustomerPrefix(),
            "suffix" => $order->getCustomerSuffix(),
            "dob" => $order->getCustomerDob(),
            "taxvat" => $order->getCustomerTaxvat(),
        ];
        return $customerData;
    }

    /**
     * Get Order Payment Information function
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderPaymentInformation($order)
    {
        // Get Order Payment
        $payment = $order->getPayment();
        $paymentData = [
            "method_code" => $payment->getMethod(),
            "method_title" => $payment->getAdditionalInformation()['method_title'],
            "amount_paid" => $payment->getAmountPaid(),
            "amount_ordered" => $payment->getAmountOrdered(),
        ];
        return $paymentData;
    }

    /**
     * Get Order Shipping Information function
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderShippingInformation($order)
    {
        $shippingData = [
            "method_code" => $order->getShippingMethod(),
            "method_title" => $order->getShippingDescription(),
            "shipping_amount" => $order->getShippingAmount(),
            "shipping_discount_amount" => $order->getShippingDiscountAmount(),
            "shipping_tax_amount" => $order->getShippingTaxAmount(),
        ];
        return $shippingData;
    }

    /**
     * Get Order Items function
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderItems($order)
    {
        $customerId = $order->getCustomerId();
        // Get Order Items
        $orderItems = $order->getAllItems();
        $items = [];
        foreach ($orderItems as $item) {
            $items [] = [
                "item_id" => $item->getItemId(),
                "order_id" => $item->getOrderId(),
                "parent_item_id" => $item->getParentItemId(),
                "quote_item_id" => $item->getQuoteItemId(),
                "store_id" => $item->getStoreId(),
                "product_id" => $item->getProductId(),
                "sku" => $item->getSku(),
                "name" => $item->getName(),
                "product_type" => $item->getProductType(),
                "is_virtual" => $item->getIsVirtual(),
                "weight" => $item->getWeight(),
                "qty_ordered" => $item->getQtyOrdered(),
                "item_price" => $item->getPrice()
            ];
        }
        return $items;
    }
}
