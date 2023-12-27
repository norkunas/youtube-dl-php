<?php

declare(strict_types=1);

namespace YoutubeDl\Process;

use function str_split;
use function substr;
use function trim;

final class TableParser
{
    /**
     * @param list<string> $rows
     *
     * @return list<array<string, string>>
     */
    public static function parse(string $header, array $rows): array
    {
        $columns = self::collectColumnsAndWidths($header);

        $data = [];

        foreach ($rows as $row) {
            $rowData = [];
            $line = $row;

            foreach ($columns as $c) {
                $column = $c['column'];
                $width = $c['width'];
                if ($width !== null) {
                    $rowData[$column] = trim(substr($line, 0, $width));
                    $line = substr($line, $width);
                } else {
                    $rowData[$column] = trim($line);
                }
            }

            $data[] = $rowData;
        }

        return $data;
    }

    /**
     * @return array<int, array{column: string, width: ?int}>
     */
    private static function collectColumnsAndWidths(string $header): array
    {
        $split = str_split($header);

        $columns = [];
        $column = '';
        $columnWidth = 0;

        foreach ($split as $i => $r) {
            if ($r !== ' ') {
                if (isset($split[$i - 1]) && $split[$i - 1] === ' ') {
                    $columns[] = [
                        'column' => $column,
                        'width' => $columnWidth,
                    ];
                    $column = '';
                    $columnWidth = 0;
                }
                $column .= strtolower($r);
                ++$columnWidth;
            } else {
                ++$columnWidth;
            }
        }

        if ($column !== '') {
            $columns[] = [
                'column' => $column,
                'width' => null,
            ];
        }

        return $columns;
    }
}
