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

use App\Models\Thank;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewThank extends Notification
{
    use Queueable;

    /**
     * NewThank Constructor.
     */
    public function __construct(public string $type, public Thank $thank)
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
            'title' => $this->thank->user->username.' Has Thanked You For An Uploaded Torrent',
            'body'  => $this->thank->user->username.' has left you a thanks on Uploaded Torrent '.$this->thank->torrent->name,
            'url'   => '/torrents/'.$this->thank->torrent->id,
        ];
    }
}
