<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Helpers;

use App\Interfaces\ByteUnitsInterface;
use ByteUnits as ByteUnitsLibrary;

class ByteUnits implements ByteUnitsInterface
{
    public function bytesFromUnit(string $units): int
    {
        return ByteUnitsLibrary\parse($units)->numberOfBytes();
    }
}
