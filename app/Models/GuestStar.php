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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestStar extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $table = 'person';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function episode()
    {
        return $this->belongsToMany(Episode::class, 'episode_guest_star', 'episode_id', 'person_id');
    }
}
