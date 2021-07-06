@extends('layouts.logged-in', ['title' => __('Chat')])
@section('content')
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">{{ __('Chat') }}</a></li>
        </ul>
        <div id="tabs-1">
            <div id="chat-wrapper">
                <div id="sidebar-wrapper">
                    <div id="sidebar-scroller">
                        <div>
                            <h3>{{ __('Benutzer') }}</h3>
                            <div id="userlist"></div>
                        </div>
                        <div>
                            <h3>{{ __('Räume') }}</h3>
                            <div id="roomlist"></div>
                        </div>
                    </div>
                </div>
                <div id="message-wrapper">
                    <fieldset class="white">
                        <legend id="message-header"></legend>
                        <div id="message-scroller">
                            <div id="message-area"></div>
                        </div>
                    </fieldset>
                    <form id="chatform">
                        <fieldset class="white">
                            <legend></legend>
                            <div class="input-wrapper">
                                <div class="input-message">
                                    <input type="text" placeholder="{{ __('Nachricht') }}"
                                           name="nachricht" id="nachricht" autocomplete="off" tabindex="1">
                                </div>
                                <div class="input-prio">
                                    <label>
                                        <input type="checkbox" name="prio" id="prio" value="1" tabindex="2">
                                        {{ __('Prio-Nachricht') }}
                                    </label>
                                </div>
                                <div class="input-button">
                                    <input type="submit" name="submit" class="btnGreen"
                                           value="{{ __('absenden') }}" tabindex="3">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_scripts')
    @parent
    <script src="{{asset('js/modules/chat/chat.js')}}"></script>
@endsection
