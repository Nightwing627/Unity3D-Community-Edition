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

namespace App\Notifications;

use App\Models\Torrent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewUploadTip extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewUploadTip Constructor.
     *
     * @param $amount
     */
    public function __construct(public string $type, public string $tipper, public $amount, public Torrent $torrent)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->tipper.' Has Tipped You '.$this->amount.' BON For An Uploaded Torrent',
            'body'  => $this->tipper.' has tipped one of your Uploaded Torrents '.$this->torrent->name,
            'url'   => \sprintf('/torrents/%s', $this->torrent->id),
        ];
    }
}
