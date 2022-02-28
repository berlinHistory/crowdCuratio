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
namespace App\Http\Controllers\Auth;

use Carbon\CarbonInterface;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Spatie\WelcomeNotification\WelcomeNotification;

class MyCustomWelcomeNotification extends WelcomeNotification
{

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $settingsContent;

    public function __construct(CarbonInterface $validUntil, $firstName, $settingsContent)
    {
        parent::__construct($validUntil);
        $this->validUntil = $validUntil;
        $this->firstName = $firstName;
        $this->settingsContent = $settingsContent;
    }

    public function buildWelcomeNotificationMessage(): MailMessage
    {
        if($this->settingsContent == '')
        {
            $this->settingsContent = config('project.mail.default');
        }

        return (new MailMessage())
            ->subject('Willkommen im Crowd Curatio')
            ->line($this->settingsContent)
            ->action(Lang::get('Set initial password'), $this->showWelcomeFormUrl)
            ->line(Lang::get('This welcome link will expire in :count minutes.', ['count' => $this->validUntil->diffInRealMinutes()]));
    }
}
