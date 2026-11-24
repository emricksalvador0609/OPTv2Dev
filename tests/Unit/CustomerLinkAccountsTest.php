<?php

namespace Tests\Unit;

use App\Services\CustomerLinkAccounts;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class CustomerLinkAccountsTest extends TestCase
{
    private function rows(): array
    {
        return [
            ['custno' => 'C001', 'custname' => 'Alpha Books', 'motheracct' => 'M01', 'ae' => '1001', 'department' => 'HED'],
            ['custno' => 'C002', 'custname' => 'Beta Traders', 'motheracct' => 'M02', 'ae' => '1002', 'department' => 'LIB'],
            ['custno' => 'C003', 'custname' => 'Gamma School', 'motheracct' => 'M03', 'ae' => '1001', 'department' => ''],
        ];
    }

    public function test_it_returns_one_page_and_the_total_counts(): void
    {
        $page = CustomerLinkAccounts::page($this->rows(), [
            'start' => 0,
            'length' => 2,
            'search' => '',
            'order' => 'custname',
            'dir' => 'asc',
        ]);

        $this->assertSame(3, $page['total']);
        $this->assertSame(3, $page['filtered']);
        $this->assertCount(2, $page['rows']);
        $this->assertSame('Alpha Books', $page['rows'][0]['custname']);
        $this->assertSame('Beta Traders', $page['rows'][1]['custname']);
    }

    public function test_it_returns_the_second_page(): void
    {
        $page = CustomerLinkAccounts::page($this->rows(), [
            'start' => 2,
            'length' => 25,
            'search' => '',
            'order' => 'custname',
            'dir' => 'asc',
        ]);

        $this->assertCount(1, $page['rows']);
        $this->assertSame('Gamma School', $page['rows'][0]['custname']);
    }

    public function test_it_sorts_descending(): void
    {
        $page = CustomerLinkAccounts::page($this->rows(), [
            'start' => 0,
            'length' => 25,
            'search' => '',
            'order' => 'custname',
            'dir' => 'desc',
        ]);

        $this->assertSame('Gamma School', $page['rows'][0]['custname']);
        $this->assertSame('Alpha Books', $page['rows'][2]['custname']);
    }

    public function test_search_is_case_insensitive_and_reports_filtered_count(): void
    {
        $page = CustomerLinkAccounts::page($this->rows(), [
            'start' => 0,
            'length' => 25,
            'search' => 'BETA',
            'order' => 'custname',
            'dir' => 'asc',
        ]);

        $this->assertSame(3, $page['total']);
        $this->assertSame(1, $page['filtered']);
        $this->assertSame('Beta Traders', $page['rows'][0]['custname']);
    }

    public function test_search_matches_customer_code_and_ae(): void
    {
        $expected = ['C003' => 1, '1001' => 2];

        foreach ($expected as $term => $count) {
            $page = CustomerLinkAccounts::page($this->rows(), [
                'start' => 0,
                'length' => 25,
                'search' => $term,
                'order' => 'custname',
                'dir' => 'asc',
            ]);

            $this->assertSame($count, $page['filtered'], 'search term: ' . $term);
        }
    }

    public function test_start_beyond_the_end_returns_no_rows(): void
    {
        $page = CustomerLinkAccounts::page($this->rows(), [
            'start' => 50,
            'length' => 25,
            'search' => '',
            'order' => 'custname',
            'dir' => 'asc',
        ]);

        $this->assertSame(3, $page['total']);
        $this->assertSame([], $page['rows']);
    }

    public function test_request_params_map_column_indexes_and_clamp_length(): void
    {
        $request = Request::create('/datatable_customer_link_accounts', 'GET', [
            'draw' => '4',
            'start' => '50',
            'length' => '5000',
            'search' => ['value' => '  alpha  '],
            'order' => [['column' => '2', 'dir' => 'DESC']],
        ]);

        $params = CustomerLinkAccounts::requestParams($request);

        $this->assertSame(4, $params['draw']);
        $this->assertSame(50, $params['start']);
        $this->assertSame(25, $params['length'], 'A too-large length must fall back to 25.');
        $this->assertSame('alpha', $params['search']);
        $this->assertSame('ae', $params['order']);
        $this->assertSame('desc', $params['dir']);
    }

    public function test_cache_key_ignores_order_and_whitespace(): void
    {
        $this->assertSame(
            CustomerLinkAccounts::cacheKey(['1002', ' 1001 ']),
            CustomerLinkAccounts::cacheKey(['1001', '1002'])
        );
    }

    public function test_cache_key_differs_for_different_teams(): void
    {
        $this->assertNotSame(
            CustomerLinkAccounts::cacheKey(['1001']),
            CustomerLinkAccounts::cacheKey(['1002'])
        );
    }
}
