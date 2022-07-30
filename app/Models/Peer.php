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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\Peer.
 *
 * @property int                             $id
 * @property string|null                     $peer_id
 * @property string|null                     $md5_peer_id
 * @property string|null                     $info_hash
 * @property string|null                     $ip
 * @property int|null                        $port
 * @property string|null                     $agent
 * @property int|null                        $uploaded
 * @property int|null                        $downloaded
 * @property int|null                        $left
 * @property int|null                        $seeder
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null                        $torrent_id
 * @property int|null                        $user_id
 * @property bool                            $connectable
 * @property-read \App\Models\Torrent $seed
 * @property-read \App\Models\Torrent|null $torrent
 * @property-read \App\Models\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereDownloaded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereInfoHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereMd5PeerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer wherePeerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereSeeder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereTorrentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereUploaded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Peer whereUserId($value)
 * @mixin \Eloquent
 */
class Peer extends Model
{
    use HasFactory;
    use Sortable;

    /**
     * The Columns That Are Sortable.
     *
     * @var array
     */
    public $sortable = [
        'id',
        'agent',
        'uploaded',
        'downloaded',
        'left',
        'seeder',
        'created_at',
    ];

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

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
     * Belongs To A Seed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seed()
    {
        return $this->belongsTo(Torrent::class, 'torrents.id', 'torrent_id');
    }

    /**
     * Updates Connectable State If Needed.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     *
     * @var resource
     */
    public function updateConnectableStateIfNeeded(): void
    {
        if (\config('announce.connectable_check') == true) {
            $tmp_ip = $this->ip;

            // IPv6 Check
            if (filter_var($tmp_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $tmp_ip = '['.$tmp_ip.']';
            }

            if (! \cache()->has('peers:connectable:'.$tmp_ip.'-'.$this->port.'-'.$this->agent)) {
                $con = @fsockopen($tmp_ip, $this->port, $_, $_, 1);

                $this->connectable = \is_resource($con);
                \cache()->put('peers:connectable:'.$tmp_ip.'-'.$this->port.'-'.$this->agent, $this->connectable, now()->addSeconds(config('announce.connectable_check_interval')));

                if (\is_resource($con)) {
                    \fclose($con);
                }

                // See https://laracasts.com/discuss/channels/eloquent/use-update-without-updating-timestamps?page=1&replyId=680133
                self::where('ip', '=', $tmp_ip)->where('port', '=', $this->port)->where('agent', '=', $this->agent)->update(['connectable' => $this->connectable, 'updated_at' => DB::raw('updated_at')]);
            }
        }
    }
}
