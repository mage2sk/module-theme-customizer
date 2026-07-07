<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\ViewModel;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class HeaderIcons implements ArgumentInterface
{
    private $customerSession;

    private $urlBuilder;

    public function __construct(
        CustomerSession $customerSession,
        UrlInterface $urlBuilder
    ) {
        $this->customerSession = $customerSession;
        $this->urlBuilder = $urlBuilder;
    }

    public function isLoggedIn(): bool
    {
        return (bool) $this->customerSession->isLoggedIn();
    }

    public function getUrl(string $route, array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
