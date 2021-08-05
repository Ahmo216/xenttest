@extends('layouts.logged-in', ['title' => 'Welcome', 'secondSubHeadLine' => $userName])
@section('content')
    <script>
        $(document).ready(function () {

            @if($updateKey ?? false)
            $.ajax({
                url: 'index.php?module=welcome&action=start&cmd=updatekey',
                type: 'POST',
                dataType: 'text',
                data: {},
                success: function () {
                    $.ajax({
                        url: 'index.php?module=appstore&action=buy&cmd=getbuylist',
                        type: 'POST',
                        dataType: 'text',
                        data: {}
                    });
                }
            });
            @endif

            $('#linkspopup').dialog({
                modal: true,
                autoOpen: false,
                minWidth: 700,
                title: '{{ __("Links Editieren") }}',
                buttons: {
                    '{{ __("SPEICHERN") }}': function () {
                        $(this).find('form').submit();
                    },
                    '{{ __("ABBRECHEN") }}': function () {
                        $(this).dialog('close');
                    }
                },
                close: function (event, ui) {
                }
            });
        });

        function editlinks() {
            $('#linkspopup').dialog('open');
        }
    </script>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1"></a></li>
        </ul>
        <div id="tabs-1">
            {!! $MESSAGE !!}
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="inside">
                        <fieldset class="home-calendar">
                            <div>{!! $KALENDER !!}</div>
                        </fieldset>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">

                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="inside">
                                <fieldset class="home-bookmarks">
                                    <legend>
                                        {{ __('Favoriten') }}
                                        <a href="#" class="edit"><img onclick="editlinks();"
                                                                      src="./themes/{{ $theme }}/images/gear.png"
                                            ></a>
                                    </legend>
                                    <div class="tabsbutton">
                                        @foreach($links as $link)
                                            @if(!empty($link['name']))
                                                <a href="{{ $link['link'] }}"
                                                   {{ $link['intern'] ? '' : 'target="_blank"' }}  class="button button-primary"
                                                >{{ $link['name'] }}</a>
                                            @endif
                                        @endforeach
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="row-height">
                            <div class="col-xs-12 col-md-6 col-md-height">
                                <div class="inside inside-full-height">
                                    <fieldset class="home-wiki">
                                        <legend>{{ __('Intranet') }} <a class="edit"
                                                                        href="index.php?module=wiki&action=edit&cmd=StartseiteWiki"
                                            ><img src="./themes/{{ $THEME }}/images/edit.svg"></a></legend>
                                        <div class="wiki">
                                            {!! $WIKI_HOMEPAGE_CONTENT !!}<br><br>
                                        </div>
                                        {{--<div>{!! $ACCORDION !!}</div>--}}
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6 col-md-height">
                                <div class="inside inside-half-height">
                                    <fieldset class="home-events">
                                        <legend>{{ __('Termine Heute') }}</legend>
                                        <ul>{!! $TERMINE ?? '' !!}</ul>
                                    </fieldset>
                                </div>
                                <div class="inside inside-half-height">
                                    <fieldset class="home-events">
                                        <legend>{{ __('Termine Morgen') }}</legend>
                                        <ul>{!! $TERMINEMORGEN ?? '' !!}</ul>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="row-height">
                            <div class="col-xs-12 col-md-{{ $userIsAdmin ? '6' : '12' }} col-md-height">
                                <div class="inside inside-full-height">
                                    <fieldset class="home-tasks">
                                        <legend>{{ __('Aufgaben') }}</legend>
                                        <div>
                                            <table width="100%" border="0">
                                                @forelse ($todoForUser as $todo)
                                                    @php
                                                        $initiator = $todo['initiator'] != $todo['adresse'] ? ' von ' . $todo['initiatorName'] : '';
                                                        $prio = $todo['prio'] == 1 ? '&nbsp;(<font color="red"><strong>Prio</strong></font>)' : '';
                                                    @endphp
                                                    <tr>
                                                        <td width="90%">{{ $todo['aufgabe'] }}{{ $initiator }}{!! $prio !!}</td>
                                                        <td width="10%">
                                                    <span style="cursor:pointer"
                                                          onclick="if(!confirm('Wirklich {{ $todo['aufgabe'] }} bearbeiten?')) return false; else AufgabenEdit({{ $todo['id'] }});"
                                                    >
                                                        <img src="./themes/new/images/edit.svg" alt="bearbeiten">
                                                    </span>
                                                            <span style="cursor:pointer"
                                                                  onclick="if(!confirm('Wirklich {{ $todo['aufgabe'] }} abschließen?')) return false; else window.location.href='index.php?module=aufgaben&action=abschluss&id={{ $todo['id'] }}&referrer=1';"
                                                            >
                                                        <img src="./themes/new/images/forward.svg" alt="abschließen">
                                                    </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td>
                                                            <center><i>{{ __('Keine Aufgaben für die Startseite') }}</i>
                                                            </center>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </table>
                                            @if ($todoForMitarbeiter)
                                                <h4>Vergebene Aufgaben:</h4>
                                                <table width="100%" border="0">
                                                    @foreach ($todoForMitarbeiter as $todo)
                                                        <tr>
                                                            <td>{{ $todo['aufgabe']}}{!! $todo['prio'] == 1 ? '&nbsp;(Prio)' : ''; !!}
                                                                <br><font style="font-size:8pt"
                                                                >für&nbsp;{{ $todo['mitarbeiterName'] }}</font></td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            @endif
                                        </div>
                                        <a href="index.php?module=aufgaben&action=list" class="button button-secondary"
                                        >{{ __('Alle Aufgaben') }}</a>
                                    </fieldset>
                                </div>
                            </div>
                            @if($userIsAdmin === true)
                                <div class="col-xs-12 col-md-6 col-md-height">
                                    <div class="inside inside-full-height">
                                        <fieldset class="home-tasks">
                                            <legend>{{ __('Learning Dashboard') }}</legend>
                                            {!! $LEARNINGDASHBOARDTILE !!}
                                            <p>In unserem Learning Dashboard zeigen wir Euch mit unserem Klick-by-Klick
                                                Assistenten und vielen Videos wie Ihr Euch einrichten und direkt mit
                                                xentral durchstarten könnt.</p>
                                            <a href="index.php?module=learningdashboard&action=list"
                                               class="button button-secondary"
                                            >
                                                <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path d="M4.47287 12.0104C2.04566 9.80074 1.66708 6.11981 3.59372 3.46237C5.52036 0.804943 9.13654 0.0202146 11.9914 1.64005"
                                                          stroke="#94979E" stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                    />
                                                    <path d="M2.21273 11.9649C1.39377 13.3996 1.11966 14.513 1.58214 14.9761C2.2843 15.6776 4.48124 14.6858 7.02522 12.6684"
                                                          stroke="#94979E" stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                    />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M9.93719 12.1581L7.52014 9.74109L12.8923 4.3689C13.3305 3.93091 13.8797 3.62049 14.481 3.47095L15.863 3.12392C16.0571 3.07558 16.2623 3.1325 16.4037 3.27392C16.5451 3.41534 16.602 3.62054 16.5537 3.8146L16.208 5.19732C16.0578 5.7984 15.7469 6.34731 15.3087 6.78527L9.93719 12.1581Z"
                                                          stroke="#94979E" stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                    />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M7.51976 9.7409L5.54021 9.08128C5.44619 9.05019 5.37505 8.97252 5.35233 8.87613C5.32961 8.77974 5.35857 8.67847 5.42881 8.60867L6.11882 7.91866C6.7306 7.30697 7.63548 7.09343 8.45619 7.36706L9.53644 7.72625L7.51976 9.7409Z"
                                                          stroke="#94979E" stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                    />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M9.93713 12.1584L10.5968 14.1386C10.6278 14.2326 10.7055 14.3038 10.8019 14.3265C10.8983 14.3492 10.9996 14.3203 11.0694 14.25L11.7594 13.56C12.3711 12.9482 12.5846 12.0434 12.311 11.2226L11.9518 10.1424L9.93713 12.1584Z"
                                                          stroke="#94979E" stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                    />
                                                </svg> {{ __('Starte hier!') }}</a>
                                        </fieldset>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="row-height">
                    <div class="col-xs-12 col-md-6 col-md-height">
                        <div class="inside inside-full-height">
                            <fieldset class="home-news">
                                <legend>{{ __('Neues von Xentral') }}</legend>
                                <div>{!! $EXTERNALNEWS !!}</div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3 col-md-height">
                        <div class="inside inside-full-height">
                            <fieldset>
                                <legend>{{ __('Update Center') }}</legend>
                                <div>
                                    <div>
                                        <p>{{ __('Ihre Version') }}: {!! $REVISION !!}</p>
                                        <h4>{{ __('Neueste Updates') }}:</h4>
                                        <table>
                                            {!! $WAIWISONFEEDS !!}
                                        </table>
                                        <div>{!! $INFO !!}</div>
                                        <p>
                                            {!! $STARTBUTTON !!}
                                            @if($userIsAdmin === true)
                                                <a href="update.php?rand={{ md5(microtime(true)) }}"
                                                   class="button button-primary" target="_blank"
                                                >{{ __('Update') }}</a>
                                            @endif
                                            {!! $BEFORECHANGELOG !!}
                                            <a href="index.php?module=welcome&action=changelog"
                                               class="button button-secondary"
                                            >{{ __('Changelog') }}</a>
                                            {!! $AFTERCHANGELOG !!}
                                            {!! $UPDATEBUTTONS_HOOK1 !!}
                                            @if(!empty(\erpAPI::Ioncube_Property('testlizenz')))
                                                {!! $TESTBUTTON !!}
                                            @endif

                                            {!! $ENDEBUTTON !!}
                                        </p>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3 col-md-height">
                        <div class="inside inside-full-height">
                            <fieldset class="home-appstore">
                                <legend>AppStore</legend>
                                <div class="home-appstore-image">
                                    <img src="./themes/{{ $theme }}/images/app-icon.png" align="center" alt="AppStore">
                                </div>
                                <div class="home-appstore-button">
                                    <a href="index.php?module=appstore&action=list" class="button button-secondary"
                                       target="_blank"
                                    >{{ __('zum AppStore') }}</a>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="linkspopup">
        <form method="POST">
            <table>
                <tr>
                    <td><b>{{ __('Name') }}</b></td>
                    <td><b>{{ __('Link') }}</b></td>
                    <td><b>{{ __('nicht in neuem Tab') }}</b></td>
                </tr>
                @foreach($links as $link)
                    <tr>
                        <td><input type="text" name="linkname{{ $loop->iteration }}" value="{{ $link['name'] }}"/>
                        </td>
                        <td><input type="text" size="50" name="linklink{{ $loop->iteration }}"
                                   value="{{ $link['link'] }}"
                            /></td>
                        <td><input type="checkbox" value="1"
                                   name="linkintern{{ $loop->iteration }}" {{ $link['intern'] ? '' : 'checked="checked"' }}/>
                        </td>
                    </tr>
                @endforeach
            </table>
            <input type="hidden" name="savelinks" value="1"/>
        </form>
    </div>

    {!! $AUFGABENPOPUP !!}
@endsection
