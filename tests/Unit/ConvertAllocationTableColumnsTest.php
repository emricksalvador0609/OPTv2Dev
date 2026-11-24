<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Guard for the convert-allocation list table.
 *
 * DataTables 1.10 reads aoColumns[i].sWidth for every <th> in the header. When a
 * dTable() call passes fewer column definitions than the table has header cells,
 * aoColumns[last] is undefined and the browser throws
 * "Cannot read properties of undefined (reading 'sWidth')".
 *
 * The period-change handler used to re-initialize the table without the History
 * column, which crashed the page as soon as a projection period was selected.
 */
class ConvertAllocationTableColumnsTest extends TestCase
{
    private const TABLE_ID = 'convert-alloc-list-table';

    private const EXPECTED_COLUMNS = [
        'num',
        'isbn',
        'description',
        'converttypedisplay',
        'qty',
        'branchwhouse',
        'status',
        'history',
        'datecreate',
        'action',
    ];

    private function view(): string
    {
        $path = dirname(__DIR__, 2) . '/resources/views/convert_allocation.blade.php';

        $this->assertFileExists($path);

        return (string) file_get_contents($path);
    }

    /**
     * @return list<list<string>>
     */
    private function columnLists(): array
    {
        preg_match_all(
            '/var\s+convertAllocListableColumns\s*=\s*\[(.*?)\];/s',
            $this->view(),
            $matches
        );

        $lists = [];

        foreach ($matches[1] as $block) {
            preg_match_all('/"data"\s*:\s*"([^"]+)"/', $block, $keys);
            $lists[] = $keys[1];
        }

        return $lists;
    }

    public function test_the_list_table_header_has_ten_columns(): void
    {
        $view = $this->view();

        $start = strpos($view, 'id="' . self::TABLE_ID . '"');
        $this->assertNotFalse($start, 'convert-alloc-list-table not found in the view.');

        $end = strpos($view, '</table>', $start);
        $this->assertNotFalse($end, 'convert-alloc-list-table header is not closed.');

        $table = substr($view, $start, $end - $start);

        $this->assertSame(
            count(self::EXPECTED_COLUMNS),
            preg_match_all('/<th\s/', $table),
            'Header cell count changed. Update the column lists and this test together.'
        );
    }

    public function test_every_initialization_uses_the_same_columns_as_the_header(): void
    {
        $lists = $this->columnLists();

        $this->assertGreaterThanOrEqual(
            2,
            count($lists),
            'Expected the initial load and the period-change handler to define columns.'
        );

        foreach ($lists as $index => $keys) {
            $this->assertSame(
                self::EXPECTED_COLUMNS,
                $keys,
                "convertAllocListableColumns block #{$index} does not match the table header."
            );
        }
    }
}
