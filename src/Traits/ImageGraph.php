<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Image Graph
 *
 * The ImageGraph API lets you generate beautiful static PNG graphs
 * for any existing Matomo report.
 */
trait ImageGraph
{
    /**
     * Generate a png image graph.
     *
     * @param  string  $apiModule Module
     * @param  string  $apiAction Action
     * @param  string  $graphType 'evolution', 'verticalBar', 'pie' or '3dPie'
     * @param  bool|string  $aliasedGraph By default, Graphs are "smooth" (anti-aliased). If you are
     *                              generating hundreds of graphs and are concerned with performance,
     *                              you can set aliasedGraph=0. This will disable anti aliasing and
     *                              graphs will be generated faster, but look less pretty.
     * @param  array  $colors Use own colors instead of the default. The colors have to be in hexadecimal
     *                      value without '#'.

     * @throws InvalidRequestException
     */
    public function getImageGraph(
        string $apiModule,
        string $apiAction,
        string $graphType = '',
        string $outputType = '0',
        string $columns = '',
        string $labels = '',
        string $showLegend = '1',
        int|string $width = '',
        int|string $height = '',
        int|string $fontSize = '9',
        string $legendFontSize = '',
        bool|string $aliasedGraph = '1',
        string $idGoal = '',
        array $colors = [],
        array $optional = []
    ): bool|object
    {
        return $this->_request('ImageGraph.get', [
            'apiModule' => $apiModule,
            'apiAction' => $apiAction,
            'graphType' => $graphType,
            'outputType' => $outputType,
            'columns' => $columns,
            'labels' => $labels,
            'showLegend' => $showLegend,
            'width' => $width,
            'height' => $height,
            'fontSize' => $fontSize,
            'legendFontSize' => $legendFontSize,
            'aliasedGraph' => $aliasedGraph,
            'idGoal ' => $idGoal,
            'colors' => $colors,
        ], $optional, self::FORMAT_PHP);
    }
}
