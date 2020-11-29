<?php

namespace Huawei\IAP;

/**
 * Class StoreSiteSelectorChoice
 *
 * A result of StoreSiteSelector
 * Can be useful for statistics. For example, to measure request time between different store sites.
 *
 * @package Huawei\IAP
 */
class StoreSiteSelectorChoice
{
    private $country;
    private $siteUrl;
    private $appTouchSite;

    public function __construct(string $country, string $siteUrl, bool $appTouchSite)
    {
        $this->country = $country;
        $this->siteUrl = $siteUrl;
        $this->appTouchSite = $appTouchSite;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getSiteUrl(): string
    {
        return $this->siteUrl;
    }

    /**
     * @return bool
     */
    public function isAppTouchSite(): bool
    {
        return $this->appTouchSite;
    }
}
