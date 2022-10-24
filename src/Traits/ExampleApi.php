<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Example API
 *
 * @link https://github.com/matomo-org/matomo/tree/4.x-dev/plugins/ExampleAPI
 */
trait ExampleApi
{
    /**
     * Get the Matomo version.
     *
     * @throws InvalidRequestException
     */
    public function getExampleMatomoVersion(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getMatomoVersion', [], $optional);
    }

    /**
     * http://en.wikipedia.org/wiki/Phrases_from_The_Hitchhiker%27s_Guide_to_the_Galaxy#The_number_42
     *
     * @throws InvalidRequestException
     */
    public function getExampleAnswerToLife(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getAnswerToLife', [], $optional);
    }

    /**
     * Unknown.
     *
     * @throws InvalidRequestException
     */
    public function getExampleObject(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getObject', [], $optional);
    }

    /**
     * Get the sum of the parameters
     *
     * @throws InvalidRequestException
     */
    public function getExampleSum(int $a = 0, int $b = 0, array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getSum', [
            'a' => $a,
            'b' => $b,
        ], $optional);
    }

    /**
     * Returns nothing but the success of the request.
     *
     * @throws InvalidRequestException
     */
    public function getExampleNull(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getNull', [], $optional);
    }

    /**
     * Get a short Matomo description.
     *
     * @throws InvalidRequestException
     */
    public function getExampleDescriptionArray(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getDescriptionArray', [], $optional);
    }

    /**
     * Get a short comparison with other analytic software.
     *
     * @throws InvalidRequestException
     */
    public function getExampleCompetitionDatatable(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getCompetitionDatatable', [], $optional);
    }

    /**
     * Get information about 42.
     * http://en.wikipedia.org/wiki/Phrases_from_The_Hitchhiker%27s_Guide_to_the_Galaxy#The_number_42
     *
     * @throws InvalidRequestException
     */
    public function getExampleMoreInformationAnswerToLife(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getMoreInformationAnswerToLife', [], $optional);
    }

    /**
     * Get a multidimensional array.
     *
     * @throws InvalidRequestException
     */
    public function getExampleMultiArray(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getMultiArray', [], $optional);
    }

}
