<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Custom Dimensions
 *
 * The Custom Dimensions API lets you manage and access reports for your
 * configured Custom Dimensions.
 */
trait CustomDimensions
{
    /**
     * Fetch a report for the given idDimension. Only reports for active
     * dimensions can be fetched. Requires at least view access.
     *
     * @throws InvalidRequestException
     */
    public function getCustomDimension(int $idDimension, array $optional = []): object|bool
    {
        return $this->_request('CustomDimensions.getCustomDimension', [
            'idDimension' => $idDimension,
        ], $optional);
    }

    /**
     * Configures a new Custom Dimension. Note that Custom Dimensions cannot be
     * deleted, be careful when creating one as you might run quickly out of
     * available Custom Dimension slots. Requires at least Admin access for the
     * specified website. A current list of available `$scopes` can be fetched
     * via the API method `CustomDimensions.getAvailableScopes()`. This method
     * will also contain information whether actually Custom Dimension slots are
     * available or whether they are all already in use.
     *
     * @param  string  $name The name of the dimension
     * @param  string  $scope Either 'visit' or 'action'. To get an up-to-date list of available scopes fetch the
     *                      API method `CustomDimensions.getAvailableScopes`
     * @param  int  $active '0' if dimension should be inactive, '1' if dimension should be active
     *
     * @throws InvalidRequestException
     */
    public function configureNewCustomDimension(string $name, string $scope, int $active, array $optional = []): object|bool
    {
        return $this->_request('CustomDimensions.configureNewCustomDimension', [
            'name' => $name,
            'scope' => $scope,
            'active' => $active,
        ], $optional);
    }

    /**
     * Updates an existing Custom Dimension. This method updates all values, you
     * need to pass existing values of the dimension if you do not want to reset
     * any value. Requires at least Admin access for the specified website.
     *
     * @param  int  $idDimension The id of a Custom Dimension.
     * @param  string  $name The name of the dimension
     * @param  int  $active '0' if dimension should be inactive, '1' if dimension should be active
     *
     * @throws InvalidRequestException
     */
    public function configureExistingCustomDimension(int $idDimension, string $name, int $active, array $optional = []): object|bool
    {
        return $this->_request('CustomDimensions.configureExistingCustomDimension', [
            'idDimension' => $idDimension,
            'name' => $name,
            'active' => $active,
        ], $optional);
    }

    /**
     * Unknown.
     *
     * @throws InvalidRequestException
     */
    public function getConfiguredCustomDimensions(): object|bool
    {
        return $this->_request('CustomDimensions.getConfiguredCustomDimensions', [
        ]);
    }

    /**
     * Get a list of all supported scopes that can be used in the API method
     * `CustomDimensions.configureNewCustomDimension`. The response also
     * contains information whether more Custom Dimensions can be created
     * or not. Requires at least Admin access for the specified website.
     *
     * @throws InvalidRequestException
     */
    public function getAvailableScopes(): object|bool
    {
        return $this->_request('CustomDimensions.getAvailableScopes', [
        ]);
    }

    /**
     * Get a list of all available dimensions that can be used in an extraction.
     * Requires at least Admin access to one website.
     *
     * @throws InvalidRequestException
     */
    public function getAvailableExtractionDimensions(): object|bool
    {
        return $this->_request('CustomDimensions.getAvailableExtractionDimensions', [
        ]);
    }
}
