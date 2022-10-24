<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Feedback
 *
 * API for plugin Feedback.
 */
trait Feedback
{
    /**
     * Get a multidimensional array.
     *
     * @throws InvalidRequestException
     */
    public function sendFeedbackForFeature(string $featureName, string $like, string $message = '', array $optional = []): bool|object
    {
        return $this->_request('Feedback.sendFeedbackForFeature', [
            'featureName' => $featureName,
            'like' => $like,
            'message' => $message,
        ], $optional);
    }
}
