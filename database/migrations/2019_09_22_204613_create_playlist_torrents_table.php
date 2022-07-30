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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_torrents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->nullable();
            $table->integer('playlist_id')->default(0)->index();
            $table->integer('torrent_id')->default(0)->index();
            $table->integer('tmdb_id')->default(0)->index();

            $table->unique(['playlist_id', 'torrent_id', 'tmdb_id']);
        });
    }
}
