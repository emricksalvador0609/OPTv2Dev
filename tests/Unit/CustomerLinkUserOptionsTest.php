<?php

namespace Tests\Unit;

use App\Services\CustomerLinkUserOptions;
use PHPUnit\Framework\TestCase;

class CustomerLinkUserOptionsTest extends TestCase
{
    public function test_it_renders_one_link_per_user(): void
    {
        $html = CustomerLinkUserOptions::html([
            (object) ['PERNR' => '1001', 'FULLNAME' => 'Ana Cruz'],
            (object) ['PERNR' => '1002', 'FULLNAME' => 'Ben Reyes'],
        ]);

        $this->assertSame(2, substr_count($html, 'class="dropdown-item p-1 text-primary btn-linkto"'));

        $this->assertStringContainsString('data-linktopernr="1001"', $html);
        $this->assertStringContainsString('data-linktopernrname="Ana Cruz"', $html);
        $this->assertStringContainsString('1001 - Ana Cruz', $html);
    }

    public function test_it_escapes_names_and_ids(): void
    {
        $html = CustomerLinkUserOptions::html([
            ['PERNR' => '10"01', 'FULLNAME' => 'Ana "Batch" O\'Neil'],
        ]);

        $this->assertStringNotContainsString('data-linktopernr="10"01"', $html);
        $this->assertStringContainsString('data-linktopernr="10&quot;01"', $html);
        $this->assertStringContainsString('data-linktopernrname="Ana &quot;Batch&quot; O&#039;Neil"', $html);
    }

    public function test_empty_list_returns_empty_string(): void
    {
        $this->assertSame('', CustomerLinkUserOptions::html([]));
    }

    /**
     * The screen used to embed the full user list in every customer row, which
     * exhausted memory once a team had a few hundred customers. The row payload
     * must stay free of the list and only carry a placeholder.
     */
    public function test_customer_rows_no_longer_embed_the_user_list(): void
    {
        $path = dirname(__DIR__, 2) . '/app/Http/Controllers/Mkacontrol.php';
        $source = (string) file_get_contents($path);

        $start = strpos($source, 'public function datatable_customer_link_accounts');
        $end = strpos($source, 'public function datatable_customer_link_user_options');

        $this->assertNotFalse($start);
        $this->assertNotFalse($end);
        $this->assertGreaterThan($start, $end);

        $method = substr($source, $start, $end - $start);

        $this->assertStringNotContainsString(
            'filter_user_list',
            $method,
            'The row payload must not build the user list again.'
        );

        $this->assertStringNotContainsString(
            'pernrOptions',
            $method,
            'The row payload must not paste the user list into every row.'
        );

        $this->assertStringContainsString('linkto-user-options', $method);

        $this->assertStringContainsString(
            'CustomerLinkAccounts::rows',
            $method,
            'The endpoint must read the cached snapshot instead of running the heavy query per request.'
        );

        $this->assertStringContainsString('recordsTotal', $method);
        $this->assertStringContainsString('recordsFiltered', $method);
    }
}
