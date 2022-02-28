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

use App\Models\Imprint;
use App\Models\MailSetting;
use App\Models\PrivacyPolicy;
use App\Models\TermsConditions;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $terms = !empty(TermsConditions::first()) ? TermsConditions::first() : null ;
        $privacy = !empty(PrivacyPolicy::first()) ? PrivacyPolicy::first() : null;
        $imprint = !empty(Imprint::first()) ? Imprint::first() : null;
        $mail = !empty(MailSetting::first()) ? MailSetting::first() : null;

        return view('settings.index', compact('terms', 'privacy', 'mail','imprint'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //Store terms and conditions
        if (isset($request->termsConditions)){

            if(isset($request->idTerms)){
                $model = TermsConditions::findOrFail($request->idTerms);
                $model->setTranslation('terms_conditions', app()->getLocale(), $request->termsConditions);
                $model->save();
            }else{
                $model = new TermsConditions;
                $model->setTranslation('terms_conditions', app()->getLocale(), $request->termsConditions);
                $model->save();
            }

            return redirect()->back()->with('success', __("message_add_terms_success"));
        }

        //Store privacy policy
        if (isset($request->privacyPolicy) && !is_null($request->privacyPolicy)){

            if(isset($request->idPrivacy)){
                $model = PrivacyPolicy::findOrFail($request->idPrivacy);
                $model->setTranslation('privacy_policy', app()->getLocale(), $request->privacyPolicy);
                $model->save();
            }else{
                $model = new PrivacyPolicy;
                $model->setTranslation('privacy_policy', app()->getLocale(), $request->privacyPolicy);
                $model->save();
            }

            return redirect()->back()->with('success', __("message_add_privacy_success"));
        }

        //Store imprint
        if (isset($request->firstname) && !is_null($request->firstname)){

            Imprint::updateOrCreate(['id' => $request->IdImprint],[
                'name' => ['firstname' => $request->firstname, 'lastname' => $request->lastname],
                'address' => ['address' => $request->address, 'postcode' => $request->postcode],
                'contact' => ['phone' => $request->phone, 'fax' => $request->fax, 'email' => $request->email]
            ]);

            return redirect()->back()->with('success', __("message_add_imprint_success"));
        }

        //Store invitation email
        if (isset($request->invitation) && !is_null($request->invitation)){

            if(isset($request->IdEmail)){
                $model = MailSetting::findOrFail($request->IdEmail);
                $model->setTranslation('invitation', app()->getLocale(), $request->invitation);
                $model->save();
            }else{
                $model = new MailSetting();
                $model->setTranslation('invitation', app()->getLocale(), $request->invitation);
                $model->save();
            }

            return redirect()->back()->with('success', __("message_add_invitation_success"));
        }

        return redirect()->back();
    }

}
