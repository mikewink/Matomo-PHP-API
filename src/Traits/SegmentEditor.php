<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;
/**
 * Segment Editor
 *
 * The SegmentEditor API lets you add, update, delete custom Segments, and list
 * saved segments.
 */
trait SegmentEditor
{
    /**
     * Check if current user can add new segments.
     *
     * @throws InvalidRequestException
     */
    public function isUserCanAddNewSegment(array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.isUserCanAddNewSegment', [], $optional);
    }

    /**
     * Delete a segment.
     *
     * @throws InvalidRequestException
     */
    public function deleteSegment(int $idSegment, array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.delete', [
            'idSegment' => $idSegment,
        ], $optional);
    }

    /**
     * Update a segment.
     *
     * @throws InvalidRequestException
     */
    public function updateSegment(
        int $idSegment,
        string $name,
        string $definition,
        string $autoArchive = '',
        string $enableAllUsers = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('SegmentEditor.update', [
            'idSegment' => $idSegment,
            'name' => $name,
            'definition' => $definition,
            'autoArchive' => $autoArchive,
            'enableAllUsers' => $enableAllUsers,
        ], $optional);
    }

    /**
     * Add a segment.
     *
     * @throws InvalidRequestException
     */
    public function addSegment(string $name, string $definition, string $autoArchive = '', string $enableAllUsers = '', array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.add', [
            'name' => $name,
            'definition' => $definition,
            'autoArchive' => $autoArchive,
            'enableAllUsers' => $enableAllUsers,
        ], $optional);
    }

    /**
     * Get a segment.
     *
     * @throws InvalidRequestException
     */
    public function getSegment(int $idSegment, array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.get', [
            'idSegment' => $idSegment,
        ], $optional);
    }

    /**
     * Get all segments.
     *
     * @throws InvalidRequestException
     */
    public function getAllSegments(array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.getAll', [], $optional);
    }

}
