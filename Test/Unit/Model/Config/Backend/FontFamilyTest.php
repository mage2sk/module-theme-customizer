<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Panth\ThemeCustomizer\Model\Config\Backend\FontFamily;
use Panth\ThemeCustomizer\Model\Config\FontFamilyNormalizer;
use PHPUnit\Framework\TestCase;

class FontFamilyTest extends TestCase
{
    private function context(): Context
    {
        $context = $this->createStub(Context::class);
        $context->method('getEventDispatcher')->willReturn($this->createStub(ManagerInterface::class));
        return $context;
    }

    private function model($value): FontFamily
    {
        $model = new FontFamily(
            $this->context(),
            $this->createStub(Registry::class),
            $this->createStub(ScopeConfigInterface::class),
            $this->createStub(TypeListInterface::class),
            new FontFamilyNormalizer()
        );
        $model->setValue($value);
        return $model;
    }

    private function afterLoad(FontFamily $model): void
    {
        $method = new \ReflectionMethod(FontFamily::class, '_afterLoad');
        $method->invoke($model);
    }

    public function testBeforeSaveConvertsDoubleQuotesAndTrims(): void
    {
        $model = $this->model('  "Open Sans", sans-serif ');
        $model->beforeSave();

        $this->assertSame("'Open Sans', sans-serif", $model->getValue());
    }

    public function testBeforeSaveTurnsNullIntoEmptyString(): void
    {
        $model = $this->model(null);
        $model->beforeSave();

        $this->assertSame('', $model->getValue());
    }

    public function testAfterLoadNormalizesStoredValue(): void
    {
        $model = $this->model('"Inter", sans-serif');
        $this->afterLoad($model);

        $this->assertSame("'Inter', sans-serif", $model->getValue());
    }

    public function testAfterLoadKeepsNullValueAsNull(): void
    {
        $model = $this->model(null);
        $this->afterLoad($model);

        $this->assertNull($model->getValue());
    }
}
