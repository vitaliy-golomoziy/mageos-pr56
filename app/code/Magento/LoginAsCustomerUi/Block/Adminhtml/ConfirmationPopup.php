<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\LoginAsCustomerUi\Block\Adminhtml;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Ui\Component\Listing\Column\Store\Options as StoreOptions;
use Magento\Backend\Block\Template;
use Magento\LoginAsCustomerApi\Api\ConfigInterface;

/**
 * Admin blog post
 */
class ConfirmationPopup extends Template
{

    /**
     * Store Options
     *
     * @var StoreOptions
     */
    private $storeOptions;

    /**
     * Config
     *
     * @var ConfigInterface
     */
    private $config;

    /**
     * Json Serializer
     *
     * @var Json
     */
    private $json;


    /**
     * ConfirmationPopup constructor.
     * @param Template\Context $context
     * @param SystemStore $systemStore
     * @param StoreOptions $toreOptions
     * @param ConfigInterface $config
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        StoreOptions $toreOptions,
        ConfigInterface $config,
        Json $json,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storeOptions = $toreOptions;
        $this->config = $config;
        $this->json = $json;
    }

    /**
     * @return string
     */
    public function getJsLayout()
    {
        $layout = $this->json->unserialize(parent::getJsLayout());
        $showStoreViewOptions = $this->config->isStoreManualChoiceEnabled();

        $layout['components']['lac-confirmation-popup']['title'] = $showStoreViewOptions
            ? __('Login as Customer: Select Store View')
            : __('You are about to Login as Customer');
        $layout['components']['lac-confirmation-popup']['content'] = __('Actions taken while in "Login as Customer" will affect actual customer data.');

        $layout['components']['lac-confirmation-popup']['showStoreViewOptions'] = $showStoreViewOptions;
        $layout['components']['lac-confirmation-popup']['storeViewOptions'] = $showStoreViewOptions
            ? $this->storeOptions->toOptionArray()
            : [];

        return $this->json->serialize($layout);
    }
}
