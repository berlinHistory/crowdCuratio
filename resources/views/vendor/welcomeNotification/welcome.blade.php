<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">

        </x-slot>
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>{{__('whoops')}}</strong> {{__('message_problem_input')}}<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST">
            @csrf

            <input type="hidden" name="email" value="{{ $user->email }}"/>

            <div>
                <x-label for="password" :value="__('password')"/>

                <div>
                    <input class="block mt-1 w-full" id="password" type="password"
                           class="@error('password') is-invalid @enderror"
                           name="password" required autocomplete="new-password">

                    @error('password')
                    <span>
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <x-label for="password-confirm" :value="__('confirm_password')"/>
                <div>
                    <input class="block mt-1 w-full" id="password-confirm" type="password" name="password_confirmation"
                           required
                           autocomplete="new-password">
                </div>
            </div>

            <div class="form-check mt-4">
                <input id="policy" type="checkbox"
                       class="form-check-input"
                       name="policy">
                <label class="form-check-label">
                        Ich habe die <a href="#" data-toggle="modal" data-target="#agbs" id="agb" style="text-decoration: underline; !important;">AGBs</a>
                        und die <a href="" data-toggle="modal" data-target="#privacy" id="privacyPolicy" style="text-decoration: underline; !important;">Datenschutzerklärung</a> gelesen und bin mit der Verwendung meiner personenbezogenen Daten
                        einverstanden.
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button type="submit" class="ml-3">
                    {{ __('save_password_login') }}
                </x-button>
            </div>
        </form>
        <!-- Modal -->
        <div class="modal fade" id="agbs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">AGBs</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span id="contentAgbs"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="privacy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Datenschutzerklärung</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span id="contentPolicy"></span>
                    </div>
                </div>
            </div>
        </div>

    </x-auth-card>
</x-guest-layout>
<script>
    $('#agb').click(function () {
        $.ajax({
            type: 'GET',
            url: "{{route('auth.terms')}}",
            success: function (data) {
                let jsonPretty = JSON.stringify(data);
                $('#contentAgbs').html(JSON.parse(jsonPretty));
            }
        });
    });

    $('#privacyPolicy').click(function () {
        $.ajax({
            type: 'GET',
            url: "{{route('auth.policy')}}",
            success: function (data) {
                let jsonPrettyPolicy = JSON.stringify(data);
                $('#contentPolicy').html(JSON.parse(jsonPrettyPolicy));
            }
        });
    });
</script>