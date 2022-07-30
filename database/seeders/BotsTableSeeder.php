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

use App\Models\Bot;
use Illuminate\Database\Seeder;

class BotsTableSeeder extends Seeder
{
    private $bots;

    public function __construct()
    {
        $this->bots = $this->getBots();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->bots as $bot) {
            Bot::updateOrCreate($bot);
        }
    }

    private function getBots()
    {
        return [
            [
                'name'       => 'SystemBot',
                'slug'       => 'systembot',
                'emoji'      => '1f916',
                'command'    => 'systembot',
                'position'   => 1,
                'color'      => '#f1c40f',
                'icon'       => 'fab fa-android',
                'help'       => '{me} v0.1 Help -- Notes about / ! and @{me} tab

All [b]echo[/b] commands begin with / and echo output to current tab.
All [b]room[/b] commands begin with ! and echo output for all in current room.
All [b]private[/b] commands begin with @ or are sent via the @{me} tab. If sent via @{me} tab, no (! | / | @){command} prefix is needed.
        
Echo & Private commands:
/msg [username] [message] - Send a quick private shoutbox message.
/gift [username] [amount] [message] - Send a quick gift of [amount] to [username] with attached [message].

Available Bots:
{bots}',
                'is_protected' => 1,
                'is_systembot' => 1,
            ],
            [
                'name'       => 'NerdBot',
                'slug'       => 'nerdbot',
                'emoji'      => '1f913',
                'command'    => 'nerdbot',
                'position'   => 2,
                'color'      => '#f1c40f',
                'icon'       => 'fab fa-android',
                'help'       => '{me} v0.1 Help -- Notes about / ! and @{me} tab

All [b]echo[/b] commands begin with / and echo output to current tab.
All [b]room[/b] commands begin with ! and echo output for all in current room.
All [b]private[/b] commands begin with @ or are sent via the @{me} tab. If sent via @{me} tab, no (! | / | @){command} prefix is needed.
        
Public, Echo & Private commands:

(! | / | @)nerdbot help - Displays this help file.

(! | / | @)nerdbot banker - Displays who is currently top BON holder.
(! | / | @)nerdbot bans - Displays # of bans from site in past 24 hours.
(! | / | @)nerdbot donations - Displays the 10 most recent donations to all bots.
(! | / | @)nerdbot doubleupload - Displays # of double upload torrents available on the site.
(! | / | @)nerdbot freeleech - Displays # of freeleech torrents available on the site.
(! | / | @)nerdbot king - Displays who is the one and only king.
(! | / | @)nerdbot logins - Displays # of log ins to site in past 24 hours.
(! | / | @)nerdbot peers - Displays # of peers for torrents on the site.
(! | / | @)nerdbot registrations - Displays # of registrations to site in past 24 hours.
(! | / | @)nerdbot uploads - Displays # of uploads to site in past 24 hours.
(! | / | @)nerdbot warnings - Displays # of H&R warnings issued on site in past 24 hours.

(! | / | @)nerdbot leeched - Displays the top leeched torrent on the site.
(! | / | @)nerdbot seeded - Displays the seeded torrent on the site.
(! | / | @)nerdbot snatched - Displays the top snatched torrent on the site.

Echo & Private commands:

None.

(All NerdBot statistics are cached for 60 minutes)',
                'is_protected' => 1,
                'is_nerdbot'   => 1,
            ],
            [
                'name'         => 'CasinoBot',
                'slug'         => 'casinobot',
                'command'      => 'casinobot',
                'emoji'        => '1f3b0',
                'position'     => 3,
                'color'        => '#f1c40f',
                'icon'         => 'fab fa-android',
                'help'         => 'Coming soon',
                'is_protected' => 1,
                'is_casinobot' => 1,
            ],
            [
                'name'         => 'BetBot',
                'slug'         => 'betbot',
                'command'      => 'betbot',
                'emoji'        => '1f3b2',
                'position'     => 4,
                'color'        => '#f1c40f',
                'icon'         => 'fab fa-android',
                'help'         => 'Coming soon',
                'is_protected' => 1,
                'is_betbot'    => 1,
            ],
            [
                'name'         => 'TriviaBot',
                'slug'         => 'triviabot',
                'command'      => 'triviabot',
                'emoji'        => '2753',
                'position'     => 5,
                'color'        => '#f1c40f',
                'icon'         => 'fab fa-android',
                'help'         => 'Coming soon',
                'is_protected' => 1,
                'is_triviabot' => 1,
            ],
        ];
    }
}
