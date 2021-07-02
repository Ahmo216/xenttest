<?php

declare(strict_types=1);

namespace App\Modules\Product\Enums;

use BenSampo\Enum\Enum;

final class SerialNumberType extends Enum
{
    /** Deactivate serial number management for the product */
    public const NONE = 'keine';

    /**
     * Create your own serial numbers for the product. These can be created with your
     * own serial number generator. Serial numbers are linked to corresponding products.
     *
     * This is suitable for users who manufacture products themselves and want to assign
     * their own serial numbers.
     */
    public const OWN = 'eigene';

    /**
     * Use the serial numbers of the supplier. The serial number must be stored on the
     * delivery note for information purposes to the recipient. No serial number is
     * recorded during storage.
     *
     * This is suitable for example for intermediaries who do not need to manage
     * serial numbers.
     */
    public const ORIGINAL = 'vomprodukt';

    /**
     * Works in a similar way to the OWN option, but is based on the supplier's serial
     * numbers. This means that the serial number must also be recorded when storing
     * and relocating the goods. When booking out the goods via the delivery note, the
     * affected serial numbers must also be specified.
     *
     * This is suitable for all users who have to manage serial numbers from suppliers.
     */
    public const STORE_ORIGINAL_AND_USE = 'vomprodukteinlagern';
}
