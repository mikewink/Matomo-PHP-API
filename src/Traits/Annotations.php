<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Annotations
 *
 * API for the annotation plugin. Provides methods to create, modify, delete
 * and query annotations.
 */
trait Annotations
{
    /**
     * Add an annotation.
     *
     * @throws InvalidRequestException
     */
    public function addAnnotation(string $note, int $starred = 0, array $optional = []): bool|object
    {
        return $this->_request('Annotations.add', [
            'note' => $note,
            'starred' => $starred,
        ], $optional);
    }

    /**
     * Save an annotation.
     *
     * @throws InvalidRequestException
     */
    public function saveAnnotation(int $idNote, string $note = '', string $starred = '', array $optional = []): bool|object
    {
        return $this->_request('Annotations.save', [
            'idNote' => $idNote,
            'note' => $note,
            'starred' => $starred,
        ], $optional);
    }

    /**
     * Delete a single annotation.
     *
     * @throws InvalidRequestException
     */
    public function deleteAnnotation(int $idNote, array $optional = []): bool|object
    {
        return $this->_request('Annotations.delete', [
            'idNote' => $idNote,
        ], $optional);
    }

    /**
     * Delete all annotations.
     *
     * @throws InvalidRequestException
     */
    public function deleteAllAnnotations(array $optional = []): bool|object
    {
        return $this->_request('Annotations.deleteAll', [], $optional);
    }

    /**
     * Get a single annotation.
     *
     * @throws InvalidRequestException
     */
    public function getAnnotation(int $idNote, array $optional = []): bool|object
    {
        return $this->_request('Annotations.get', [
            'idNote' => $idNote,
        ], $optional);
    }

    /**
     * Get all annotations.
     *
     * @throws InvalidRequestException
     */
    public function getAllAnnotation(string $lastN = '', array $optional = []): bool|object
    {
        return $this->_request('Annotations.getAll', [
            'lastN' => $lastN,
        ], $optional);
    }

    /**
     * Get number of annotations for current period.
     *
     * @throws InvalidRequestException
     */
    public function getAnnotationCountForDates(int $lastN, string $getAnnotationText, array $optional = []): bool|object
    {
        return $this->_request('Annotations.getAnnotationCountForDates', [
            'lastN' => $lastN,
            'getAnnotationText' => $getAnnotationText
        ], $optional);
    }

}
