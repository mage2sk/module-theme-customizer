<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Panth\ThemeCustomizer\Model\Config\FontFamilyNormalizer;

class FontFamily extends Value
{
    private FontFamilyNormalizer $normalizer;

    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        FontFamilyNormalizer $normalizer,
        ?AbstractResource $resource = null,
        ?AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->normalizer = $normalizer;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function beforeSave()
    {
        $this->setValue($this->normalizer->normalize($this->getValue()));

        return parent::beforeSave();
    }

    protected function _afterLoad()
    {
        if ($this->getValue() !== null) {
            $this->setValue($this->normalizer->normalize($this->getValue()));
        }

        return parent::_afterLoad();
    }
}
