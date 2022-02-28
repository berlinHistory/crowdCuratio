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
namespace App\Http\Controllers;

use App\Models\PrivacyPolicy;
use App\Models\TermsConditions;
use Illuminate\Http\Request;

class PublicController extends Controller
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function projectPolicy(){

        $privacy = PrivacyPolicy::where('active',1)->first();
        $data = $privacy->privacy_policy;

        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function projectTerms(){
        $terms = TermsConditions::where('active',1)->first();
        $data = $terms->terms_conditions;

        return  response()->json($data);
    }
}
