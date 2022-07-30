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

namespace App\Achievements;

use Assada\Achievements\Achievement;

class UserMade400Comments extends Achievement
{
    /*
     * The achievement name
     */
    public $name = '400Comments';

    /*
     * A small description for the achievement
     */
    public $description = 'Wow! You have already made 400 comments!';

    /*
     * The amount of "points" this user need to obtain in order to complete this achievement
     */
    public $points = 400;
}
