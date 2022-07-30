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

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Thank.
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property int                             $torrent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Torrent $torrent
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thank query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thank whereTorrentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thank whereUserId($value)
 * @mixin \Eloquent
 */
class Thank extends Model
{
    use HasFactory;
    use Auditable;

    /**
     * Belongs To A Torrent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function torrent()
    {
        return $this->belongsTo(Torrent::class);
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
