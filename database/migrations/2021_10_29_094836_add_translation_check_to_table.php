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

class AddTranslationCheckToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->boolean('is_translated')->after('position')->default(0);
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('is_translated')->after('position')->default(0);
        });

        Schema::table('texts', function (Blueprint $table) {
            $table->boolean('is_translated')->after('copyright')->default(0);
        });

        Schema::table('sources', function (Blueprint $table) {
            $table->boolean('is_translated')->after('type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn('is_translated');
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn('is_translated');
        });

        Schema::table('texts', function (Blueprint $table) {
            $table->dropColumn('is_translated');
        });

        Schema::table('sources', function (Blueprint $table) {
            $table->dropColumn('is_translated');
        });
    }
}
