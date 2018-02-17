<?php
/**
 * Simplified POS module for Magento
 *
 * @package     Ocheretnyi_Pos
 * @author      Igor Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @copyright   Copyright 2018 Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @license     Open Source License (OSL v3)
 */

namespace Ocheretnyi\Pos\Ui\Component\Listing\Column;

/**
 * Render column block in the order grid
 */
class Pos extends \Magento\Ui\Component\Listing\Columns\Column
{

    /**
     * @var \Ocheretnyi\Pos\Model\Pos
     */
    protected $posModel;

    /**
     * @var \Ocheretnyi\Pos\Model\ResourceModel\Pos\CollectionFactory
     */
    protected $posCollectionFactory;

    /**
     * Pos constructor.
     *
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory           $uiComponentFactory
     * @param \Ocheretnyi\Pos\Model\ResourceModel\Pos\CollectionFactory    $posCollectionFactory
     * @param array                                                        $components
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Ocheretnyi\Pos\Model\ResourceModel\Pos\CollectionFactory $posCollectionFactory,
        array $components = [],
        array $data = []
    ) {
        $this->posCollectionFactory = $posCollectionFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');

            $posList = $this->getPosList();
            foreach ($dataSource['data']['items'] as & $item) {
                $value = $item[$fieldName];
                if (!empty($item[$fieldName]) && isset($posList[$value])) {
                    $item[$fieldName] = $posList[$value];
                }
            }
        }

        return $dataSource;
    }

    /**
     * @return array
     */
    protected function getPosList()
    {
        return $this->posCollectionFactory->create()->getPosList();
    }
}
