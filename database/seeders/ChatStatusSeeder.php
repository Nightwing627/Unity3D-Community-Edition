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

namespace Database\Seeders;

use App\Models\ChatStatus;
use Illuminate\Database\Seeder;

class ChatStatusSeeder extends Seeder
{
    private $statuses;

    public function __construct()
    {
        $this->statuses = $this->getStatuses();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->statuses as $status) {
            ChatStatus::updateOrCreate($status);
        }
    }

    private function getStatuses()
    {
        return [
            [
                'name'  => 'Online',
                'color' => '#2ECC40',
                'icon'  => config('other.font-awesome').' fa-comment-smile',
            ],
            [
                'name'  => 'Away',
                'color' => '#FFDC00',
                'icon'  => config('other.font-awesome').' fa-comment-minus',
            ],
            [
                'name'  => 'Busy',
                'color' => '#FF4136',
                'icon'  => config('other.font-awesome').' fa-comment-exclamation',
            ],
            [
                'name'  => 'Offline',
                'color' => '#AAAAAA',
                'icon'  => config('other.font-awesome').' fa-comment-slash',
            ],
        ];
    }
}
