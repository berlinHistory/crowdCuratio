<?php
/**
crowdCuratio - Curating together virtually
Copyright (C)2022 - berlinHistory e.V.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program in the file LICENSE.

If not, see <https://www.gnu.org/licenses/>.
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Drop entry id from texts table
        Schema::table('texts', function (Blueprint $table) {
            $table->dropForeign(['entry_id']);
            $table->dropColumn(['entry_id']);
            $table->dropColumn(['position']);
        });

        //Drop entry id from images table
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['entry_id']);
            $table->dropColumn(['entry_id']);
            $table->dropColumn(['position']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('texts', function (Blueprint $table) {
            $table->unsignedInteger('entry_id');
            $table->integer('position')->default(0)->after('copyright');

            $table->foreign('entry_id')
                ->references('id')
                ->on('entries')
                ->onDelete('cascade');
        });

        Schema::table('images', function (Blueprint $table) {
            $table->unsignedInteger('entry_id');
            $table->integer('position')->default(0)->after('alt');

            $table->foreign('entry_id')
                ->references('id')
                ->on('entries')
                ->onDelete('cascade');
        });
    }
}
