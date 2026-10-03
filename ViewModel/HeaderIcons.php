<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\ViewModel;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Panth\ThemeCustomizer\Helper\HeaderConfig;

class HeaderIcons implements ArgumentInterface
{
    private $customerSession;

    private $urlBuilder;

    private HeaderConfig $headerConfig;

    public function __construct(
        CustomerSession $customerSession,
        UrlInterface $urlBuilder,
        HeaderConfig $headerConfig
    ) {
        $this->customerSession = $customerSession;
        $this->urlBuilder = $urlBuilder;
        $this->headerConfig = $headerConfig;
    }

    public function isLoggedIn(): bool
    {
        return (bool) $this->customerSession->isLoggedIn();
    }

    public function getUrl(string $route, array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    public function isSearchIconEnabled(): bool
    {
        return $this->headerConfig->isSearchIconEnabled();
    }

    public function isAccountIconEnabled(): bool
    {
        return $this->headerConfig->isAccountIconEnabled();
    }

    public function isMinicartIconEnabled(): bool
    {
        return $this->headerConfig->isMinicartIconEnabled();
    }
}
