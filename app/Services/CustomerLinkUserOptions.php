<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Builds the "Link To" dropdown for the customer link accounts screen.
 *
 * The list used to be built inside datatable_customer_link_accounts and pasted
 * into every customer row. With ~600 users that is ~104 KB per row, so a few
 * hundred customers exhausted the 128 MB request limit. The page now fetches
 * this list once and fills each row's dropdown from it.
 */
class CustomerLinkUserOptions
{
    public const TTL = 300;

    /**
     * @param  iterable<object|array>  $users  rows with PERNR and FULLNAME
     */
    public static function html(iterable $users): string
    {
        $html = '';

        foreach ($users as $user) {
            $pernr = self::field($user, 'PERNR');
            $name = self::field($user, 'FULLNAME');

            $html .= ' <a href="#" class="dropdown-item p-1 text-primary btn-linkto"'
                . ' data-linktopernr="' . htmlspecialchars($pernr, ENT_QUOTES, 'UTF-8') . '"'
                . ' data-linktopernrname="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '"> '
                . htmlspecialchars($pernr, ENT_QUOTES, 'UTF-8') . ' - '
                . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' </a>';
        }

        return $html;
    }

    public static function forCurrentUser(): string
    {
        $identity = implode('|', [
            trim((string) session('pernr')),
            trim((string) session('rank')),
            trim((string) session('division')),
        ]);

        return Cache::remember(
            'customerlink:useroptions:' . sha1($identity),
            self::TTL,
            fn () => self::html(filter_user_list('0')->get())
        );
    }

    /**
     * @param  object|array  $user
     */
    private static function field($user, string $key): string
    {
        $value = is_array($user) ? ($user[$key] ?? '') : ($user->{$key} ?? '');

        return trim((string) $value);
    }
}
