<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * HeaderIcons ViewModel — exposes customer session + URL builder to the
 * Luma header-icons template without touching ObjectManager.
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\ViewModel;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class HeaderIcons implements ArgumentInterface
{
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param CustomerSession $customerSession
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        CustomerSession $customerSession,
        UrlInterface $urlBuilder
    ) {
        $this->customerSession = $customerSession;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Whether the current customer is logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return (bool) $this->customerSession->isLoggedIn();
    }

    /**
     * Build a frontend URL for the given route.
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route, array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
