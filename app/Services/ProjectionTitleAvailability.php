<?php

namespace App\Services;

/**
 * Availability flags for a title returned by the projection title search.
 *
 * A title can exist in SAP and still be unusable in a projection: it may have
 * no tagging row in PushListTagging, or its SAP price may still be zero. The
 * search returns those rows with a flag instead of dropping them silently, so
 * the user learns the ISBN exists instead of reading "No records found".
 *
 * Untagged titles cannot be added to a projection. A zero price is only a
 * warning: existing projections keep working the same way.
 */
class ProjectionTitleAvailability
{
    public const NOT_TAGGED = 'This ISBN is not yet tagged';

    public const NO_PRICE = 'This ISBN has no price yet';

    /**
     * @param  bool  $tagged     ISBN has a PushListTagging row with status = 1
     * @param  mixed  $unitPrice  SAP unit price (KBETRCE)
     * @return array{tagged: bool, priced: bool, selectable: bool, warning: string}
     */
    public static function flags(bool $tagged, $unitPrice): array
    {
        $priced = is_numeric($unitPrice) && (float) $unitPrice > 0;

        $warnings = [];

        if (! $tagged) {
            $warnings[] = self::NOT_TAGGED;
        }

        if (! $priced) {
            $warnings[] = self::NO_PRICE;
        }

        return [
            'tagged' => $tagged,
            'priced' => $priced,
            'selectable' => $tagged,
            'warning' => implode(' ', $warnings),
        ];
    }
}
