<?php

namespace App\Achievements;

use Assada\Achievements\Achievement;

class UserUploaded200Subtitles extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'UserUploaded200Subtitles';

    /*
     * A small description for the achievement
     */
    public $description = 'You have made 200 subtitle uploads!';

    /*
    * The amount of "points" this user need to obtain in order to complete this achievement
    */
    public $points = 200;
}
