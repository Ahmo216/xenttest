@extends('layouts.logged-out')
@section('content')
    @if($loginIsLocked === true)
        <div class="warning">
            <p>
                {{ __('Achtung: Es werden gerade Wartungsarbeiten in Ihrem System (z.B. Update oder Backup) durch Ihre IT-Abteilung durchgeführt.') }}
                .
                {{ __('Das System sollte in wenigen Minuten wieder erreichbar sein.') }}
                {{ __('Für Rückfragen wenden Sie sich bitte an Ihren Xentral-Administrator.') }}
            </p>
        </div>
    @else
        <form action="" id="frmlogin" method="post"><br>
            {{ __('Bitte gib Deinen Benutzernamen und Passwort ein!') }}

            <div class="field">
                <input style="display:none;min-width:200px;" id="chtype" type="button" value="Login mit Username / PW"/>
            </div>
            <div class="field">
                <label for="username">{{ __('Benutzer') }}:</label>
                <input type="text" id="username" name="username"/><input type="hidden" name="isbarcode" id="isbarcode"
                                                                         value="0"
                />
            </div>

            <div class="field">
                <label for="password">{{ __('Passwort') }}:</label>
                <input type="password" id="password" name="password"/>
            </div>

            <div class="field">
                <label for="token">OTP (optional)</label>
                <input type="text" autocomplete="off" id="token" name="token"/>
            </div>
            @if($loginErrorMessage)
                <span style="color:red">{{ $loginErrorMessage }}</span>
            @endif
            @if(isset($rfidAdapterbox))
                @if($rfidAdapterbox === false)
                    <input type="hidden" name="code" id="code"/>
                    <input type="password" name="stechuhrdevice" id="stechuhrdevice" size="45"/>
                @else
                    <input type="hidden" name="code" id="code"/>
                    <input type="hidden" name="rfid" id="rfid" value="{{ $rfidAdapterbox }}"/>
                    <input type="hidden" name="rfidcode" id="rfidcode"/>
                    {{ __('Bitte Scannen') }}
                @endif
            @endif
            <div class="field-row">
                @if(count($multiDatabases) > 1)
                    <div class="field">
                        <select id="db" name="db">
                            <option value="{{ $legacyConfig->WFdbname }}">- System wählen -</option>
                            @foreach($multiDatabases as $name => $label)
                                <option value="{{ $name }}">{{ $label }}</option>
                            @endforeach
                        </select><input type="hidden" name="dbselect" value="true">
                    </div>
                @endif
                <div class="field">
                    <select id="language" name="language">
                        <option value="">- {{ __('Sprache wählen') }} -</option>
                        <option {{ Str::startsWith(strtolower(request()->getPreferredLanguage()), 'de') ? ' selected="selected"' : '' }} value="german">{{ __('Deutsch') }}</option>
                        <option {{ Str::startsWith(strtolower(request()->getPreferredLanguage()), 'en') ? ' selected="selected"' : '' }} value="englisch">{{ __('English') }}</option>
                    </select>
                </div>
            </div>
            <div class="btn-wrapper field-row">
                <div class="field">
                    <input type="submit" class="btn" value="{{ __('Anmelden') }}"/>
                </div>
                <div class="field link">
                    <a href="index.php?module=welcome&action=passwortvergessen">{{ __('Passwort vergessen?') }}</a>
                </div>
            </div>
        </form>


        <script type="text/javascript">
            var siv = null;
            var intv = null;
            document.getElementById("username").focus();
            $("#isbarcode").val('0');
            $(document).ready(function () {

                $("#username").trigger('focus');
                $("#username").on('keydown', function (event) {
                    var which = event.which;
                    if (which == 13) {
                        event.preventDefault();
                        if ($("#username").val().indexOf("!!!") < 1) {
                            $('#password').focus();
                        } else {
                            $('#frmlogin').submit();
                        }
                    } else {
                        var iof = $("#username").val().indexOf("!!!");
                        if (iof > 0) {
                            $('#password').trigger('focus');
                            $('#username').val($("#username").val().substring(0, iof));
                            $("#isbarcode").val('1');
                        }
                    }
                });
                if (typeof (Storage) !== "undefined") {

                    @if($resetStorage === true)

                    if (typeof (Storage) !== "undefined") {
                        var devicecode = localStorage.getItem("devicecode");
                        if (devicecode) {
                            localStorage.setItem("devicecode", "");
                        }
                    }
                    if (typeof indexedDB != "undefined") {
                        var request = indexedDB.open('wawisionstechuhrdevice', 1);

                        request.onupgradeneeded = function () {
                            var db = this.result;
                            if (!db.objectStoreNames.contains('stechuhr')) {
                                store = db.createObjectStore('stechuhr', {
                                    keyPath: 'key',
                                    autoIncrement: true
                                });
                            }
                        };

                        request.onsuccess = function () {
                            var db = this.result;
                            var trans = db.transaction(['stechuhr'], 'readonly');
                            var store = trans.objectStore('stechuhr');

                            var range = IDBKeyRange.lowerBound(0);
                            var cursorRequest = store.openCursor(range);


                            cursorRequest.onsuccess = function (evt) {
                                var result = evt.target.result;
                                if (result) {
                                    if (typeof result.value != 'undefined' && typeof result.value.code != 'undefined') {
                                        var trans2 = db.transaction(['stechuhr'], 'readwrite');
                                        var store2 = trans2.objectStore('stechuhr');
                                        var request2 = store2.delete(result.key);
                                    }
                                }
                            }
                        }
                    }

                    @endif

                    var devicecode = localStorage.getItem("devicecode");
                    if (devicecode) {
                        checkdevicecode(devicecode);
                    } else {
                        checkindexdb();

                    }
                } else {
                    $('#stechuhrdevice').hide();

                }
            });

            function checkindexdb() {
                $('#stechuhrdevice').hide();
                if (typeof indexedDB != 'undefined') {
                    var request = indexedDB.open('wawisionstechuhrdevice', 1);

                    request.onupgradeneeded = function () {
                        var db = this.result;
                        if (!db.objectStoreNames.contains('stechuhr')) {
                            store = db.createObjectStore('stechuhr', {
                                keyPath: 'key',
                                autoIncrement: true
                            });
                        }
                    };

                    request.onsuccess = function () {
                        var db = this.result;
                        var trans = db.transaction(['stechuhr'], 'readonly');
                        var store = trans.objectStore('stechuhr');

                        var gefunden = false;
                        var range = IDBKeyRange.lowerBound(0);
                        var cursorRequest = store.openCursor(range);

                        // Wird für jeden gefundenen Datensatz aufgerufen... und einmal extra
                        cursorRequest.onsuccess = function (evt) {
                            var result = evt.target.result;
                            if (result) {
                                if (typeof result.value != 'undefined' && typeof result.value.code != 'undefined') {
                                    if (result.value.code != '') {
                                        $('#stechuhrdevice').show();
                                        if (typeof (Storage) !== "undefined") {
                                            localStorage.setItem("devicecode", result.value.code);
                                        }
                                        checkdevicecode(result.value.code);
                                    }
                                }
                            }
                        }
                    }

                } else {
                    $('#stechuhrdevice').hide();
                }
            }

            function checkdevicecode(devicecode) {
                $('#stechuhrdevice').each(function () {
                    $('#token').parent().hide();
                    $('#password').parent().hide();
                    $('#username').parent().hide();
                    $('#loginmsg').hide();
                    $('#chtype').show();
                    $('#chtype').on('click', function () {
                        $('#token').parent().show();
                        $('#password').parent().show();
                        $('#username').parent().show();
                        $('#loginmsg').show();
                        $(this).hide();
                        clearInterval(siv);
                    });
                    $('#code').val(devicecode);
                    $('#stechuhrdevice').focus();
                    $("#stechuhrdevice").on('keydown', function (event) {
                        setTimeout(function () {
                            if ($('#stechuhrdevice').val().length > 205)
                                setTimeout(function () {
                                    $('#frmlogin').submit();
                                }, 100);
                        }, 500);

                    });
                    siv = setInterval(function () {
                        $('#stechuhrdevice').focus(), 200
                    });
                });
                $('#rfid').each(function () {
                    $('#code').val(devicecode);
                    $('#token').parent().hide();
                    $('#password').parent().hide();
                    $('#username').parent().hide();
                    $('#loginmsg').hide();
                    $('#chtype').show();
                    $('#chtype').on('click', function () {
                        $('#token').parent().show();
                        $('#password').parent().show();
                        $('#username').parent().show();
                        $('#loginmsg').show();
                        $(this).hide();
                        clearInterval(siv);
                    });

                    intv = setInterval(function () {
                        checkrf(devicecode);
                    }, 1000);
                });
            }

            function checkrf(devicecode) {
                clearInterval(intv);
                intv = setInterval(function () {
                    checkrf(devicecode);
                }, 3000);
                $.ajax({
                    url: 'index.php?module=welcome&action=login&cmd=checkrfid',
                    type: 'POST',
                    dataType: 'json',
                    data: {code: devicecode},
                    success: function (data) {
                        if (typeof data.rfidcode != 'undefined' && data.rfidcode != '') {
                            clearInterval(intv);
                            if (typeof data.code != 'undefined') $('#code').val(data.code);
                            $('#rfidcode').val(data.rfidcode);
                            $('#frmlogin').submit();
                        } else {
                            if (typeof data.rfidcode != 'undefined') {
                                clearInterval(intv);
                                intv = setInterval(function () {
                                    checkrf(devicecode);
                                }, 1000);
                            }
                        }
                    }
                });
            }

        </script>
    @endif

@endsection

