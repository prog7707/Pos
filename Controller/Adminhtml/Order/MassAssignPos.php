<?php
/**
 * Simplified POS module for Magento
 *
 * @package     Ocheretnyi_Pos
 * @author      Igor Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @copyright   Copyright 2018 Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @license     Open Source License (OSL v3)
 */

namespace Ocheretnyi\Pos\Controller\Adminhtml\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Model\Order\Address\Renderer;

/**
 * Class MassAssign
 */
class MassAssignPos extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{

    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ocheretnyi_Pos::ocheretnyi_massassign_pos';

    /**
     * @var OrderManagementInterface
     */
    protected $orderManagement;
    /**
     * @var Renderer
     */
    protected $addressRenderer;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var \Ocheretnyi\Pos\Model\PosFactory
     */
    protected $posFactory;

    /**
     * MassAssignPos constructor.
     *
     * @param Context                                            $context
     * @param Filter                                             $filter
     * @param CollectionFactory                                  $collectionFactory
     * @param OrderManagementInterface                           $orderManagement
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Mail\Template\TransportBuilder  $transportBuilder
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        OrderManagementInterface $orderManagement,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Ocheretnyi\Pos\Model\PosFactory $posFactory
    ) {
        parent::__construct($context, $filter);
        $this->collectionFactory = $collectionFactory;
        $this->orderManagement = $orderManagement;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->transportBuilder = $transportBuilder;
        $this->addressRenderer = $addressRenderer;
        $this->posFactory = $posFactory;
    }

    /**
     * Update selected orders
     *
     * @param AbstractCollection $collection
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    protected function massAction(AbstractCollection $collection)
    {
        $countUpdatedOrders = 0;
        $posId = $this->getRequest()->getParam('pos');

        $model = $this->_objectManager->create('Magento\Sales\Model\Order');
        foreach ($collection->getItems() as $order) {
            if (!$order->getEntityId()) {
                continue;
            }
            $loadedOrder = $model->load($order->getEntityId());
            $loadedOrder->setPosId((int) $posId)->save();

            /**
             * send email to POS by each order
             */
            $this->notifyPOS($loadedOrder);

            $countUpdatedOrders++;
        }
        $countNonUpdatedOrders = $collection->count() - $countUpdatedOrders;

        if ($countNonUpdatedOrders && $countUpdatedOrders) {
            $this->messageManager->addError(__('%1 order(s) were not updated.', $countNonUpdatedOrders));
        } elseif ($countNonUpdatedOrders) {
            $this->messageManager->addError(__('No order(s) were updated.'));
        }

        if ($countUpdatedOrders) {
            $this->messageManager->addSuccess(__('You have updated %1 order(s).', $countUpdatedOrders));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($this->getComponentRefererUrl());
        return $resultRedirect;
    }

    /**
     * @param $posId
     *
     * @return null
     */
    protected function getPOSById($posId)
    {
        $pos = null;
        if ($posId) {
            $pos = $this->posFactory->create()->load((int) $posId);
            if ($pos) {
                return $pos;
            }
        }
        return $pos;
    }

    /**
     * @param $order
     */
    public function notifyPOS($order)
    {
        $pos = $this->getPOSById($order->getPosId());
        if (!$pos) {
            return null;
        }

        $emailTo = $pos->getData('primary_email');
        $emailBcc = $pos->getData('support_email');

        $data = [
            'order'                    => $order,
            'billing'                  => $order->getBillingAddress(),
            'payment_html'             => $this->getPaymentHtml($order),
            'store'                    => $this->storeManager->getStore($order->getStoreId()),
            'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
            'formattedBillingAddress'  => $this->getFormattedBillingAddress($order),
        ];

        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($data);

        try {
            if ($order->getCustomerIsGuest()) {
                $templateId = $this->scopeConfig->getValue(
                    \Magento\Sales\Model\Order\Email\Container\OrderIdentity::XML_PATH_EMAIL_GUEST_TEMPLATE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
            } else {
                $templateId = $this->scopeConfig->getValue(
                    \Magento\Sales\Model\Order\Email\Container\OrderIdentity::XML_PATH_EMAIL_TEMPLATE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
            }

            //order_email
            if ($emailBcc) {
                $this->transportBuilder->addBcc($emailBcc);
            }

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions(
                    [
                        'area'  => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $order->getStoreId()
                    ]
                )
                ->setTemplateVars($postObject->getData())
                ->setFrom(
                    $this->scopeConfig->getValue(
                        \Magento\Sales\Model\Order\Email\Container\OrderIdentity::XML_PATH_EMAIL_IDENTITY,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    )
                )
                ->addTo($emailTo)
                ->getTransport();

            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Email is not sent by order[%1].', $order->getIncrementId()) . $e->getMessage());
        }
    }

    /**
     * @param Order $order
     *
     * @return string|null
     */
    protected function getFormattedShippingAddress($order)
    {
        return $order->getIsVirtual()
            ? null
            : $this->addressRenderer->format($order->getShippingAddress(), 'html');
    }

    /**
     * @param Order $order
     *
     * @return string|null
     */
    protected function getFormattedBillingAddress($order)
    {
        return $this->addressRenderer->format($order->getBillingAddress(), 'html');
    }

    /**
     * Get payment info block as html
     *
     * @param Order $order
     *
     * @return string
     */
    protected function getPaymentHtml($order)
    {
        $paymentHelper = $this->_objectManager->create('\Magento\Payment\Helper\Data');
        return $paymentHelper->getInfoBlockHtml(
            $order->getPayment(),
            $order->getStoreId()
        );
    }
}
