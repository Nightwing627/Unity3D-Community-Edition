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

namespace App\Listeners;

use App\Events\TicketClosed;
use App\Models\User;
use App\Notifications\StaffTicketClosed;
use Illuminate\Support\Facades\Notification;

class NotifyStaffTicketWasClosed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     *
     * @return void
     */
    public function handle(TicketClosed $event)
    {
        $staff = User::where(['is_modo' => 1])->limit(1)->get();
        Notification::send($staff, new StaffTicketClosed($event->ticket));
    }
}
