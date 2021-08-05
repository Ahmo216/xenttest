@extends('layouts.logged-out')
@section('content')
    <form action="" id="frmlogin" method="post" autocomplete="off">

        <table>
            @if($infoMessage !== false)
                <tr>
                    <td>
                        <span id="info-message">{{ $infoMessage }}</span>
                    </td>
                </tr>
            @else
                @if($userName === false)
                    <tr>
                        <td>
                            <label for="username">{{ __('Benutzername') }}</label>
                            <input name="vergessenusername" type="text" size="45" value="" id="username"
                                   autocomplete="off"
                            >
                        </td>
                    </tr>
                @else

                    <tr>
                        <td>
                            <label for="passwort">{{ __('Passwort') }}</label>
                            <input name="passwort" type="password" size="45" value="" id="passwort">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="passwortwiederholen">{{ __('Passwort-Wiederholung') }}</label>
                            <input name="passwortwiederholen" type="password" size="45" value=""
                                   id="passwortwiederholen"
                            >
                        </td>
                    </tr>
                @endif

                <tr>
                    <td align="center">
                        @if($errorMessage !== false)
                            <span id="loginerrormsg">{{ $errorMessage }}</span>
                        @endif

                    </td>
                </tr>
                <tr>
                    <td align="center">
                        @if($userName === false)
                            <input type="submit" class="btn btn-primary" value="{{ __('Passwort zurücksetzen') }}">
                        @else
                            <input type="submit" class="btn btn-primary" name="aendern"
                                   value="{{ __('Passwort ändern') }}"
                            >
                        @endif
                    </td>
                </tr>
            @endif

            <tr>
                <td align="center">
                    <br>
                    <a href="index.php" class="link">{{ __('zurück zum Login') }}</a>
                </td>
            </tr>
        </table>
    </form>
@endsection
