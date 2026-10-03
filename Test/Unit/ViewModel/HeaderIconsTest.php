<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\ViewModel;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\UrlInterface;
use Panth\ThemeCustomizer\Helper\HeaderConfig;
use Panth\ThemeCustomizer\ViewModel\HeaderIcons;
use PHPUnit\Framework\TestCase;

class HeaderIconsTest extends TestCase
{
    private function viewModel(bool $loggedIn, array $icons = [], ?UrlInterface $url = null): HeaderIcons
    {
        $session = $this->createStub(CustomerSession::class);
        $session->method('isLoggedIn')->willReturn($loggedIn);
        $header = $this->createStub(HeaderConfig::class);
        $header->method('isSearchIconEnabled')->willReturn($icons['search'] ?? false);
        $header->method('isAccountIconEnabled')->willReturn($icons['account'] ?? false);
        $header->method('isMinicartIconEnabled')->willReturn($icons['minicart'] ?? false);

        return new HeaderIcons($session, $url ?? $this->createStub(UrlInterface::class), $header);
    }

    public function testLoginStateComesFromCustomerSession(): void
    {
        $this->assertTrue($this->viewModel(true)->isLoggedIn());
        $this->assertFalse($this->viewModel(false)->isLoggedIn());
    }

    public function testGetUrlDelegatesRouteAndParams(): void
    {
        $url = $this->createMock(UrlInterface::class);
        $url->expects($this->once())
            ->method('getUrl')
            ->with('customer/account/login', ['_secure' => true])
            ->willReturn('https://shop.test/customer/account/login/');

        $this->assertSame(
            'https://shop.test/customer/account/login/',
            $this->viewModel(false, [], $url)->getUrl('customer/account/login', ['_secure' => true])
        );
    }

    public function testIconFlagsMirrorHeaderConfig(): void
    {
        $vm = $this->viewModel(false, ['search' => true, 'account' => false, 'minicart' => true]);

        $this->assertTrue($vm->isSearchIconEnabled());
        $this->assertFalse($vm->isAccountIconEnabled());
        $this->assertTrue($vm->isMinicartIconEnabled());
    }
}
