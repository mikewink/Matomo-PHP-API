<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * SEO
 *
 * The SEO API lets you access a list of SEO metrics for the specified URL:
 * Google PageRank, Google/Bing indexed pages and age of the Domain name.
 */
trait Seo
{
    /**
     * Get the SEO rank of a URL.
     *
     * @throws InvalidRequestException
     */
    public function getSeoRank(string $url, array $optional = []): mixed
    {
        return $this->request('SEO.getRank', [
            'url' => $url,
        ], $optional);
    }
}
