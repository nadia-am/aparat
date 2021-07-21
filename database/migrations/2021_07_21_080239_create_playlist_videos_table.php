<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('palaylist_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')
                ->constrained('videos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('palaylist_id')
                ->constrained('palaylists')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('palaylist_videos');
    }
}
