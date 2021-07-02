<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="Content-Security-Policy"
          content="default-src 'self' 'unsafe-inline' 'unsafe-eval' xentral.com *.xentral.com xentral.biz *.xentral.biz *.wawision.de *.embedded-projects.net maps.googleapis.com maps.gstatic.com www.youtube.com thumbs.ebaystatic.com {!! $ADDITIONALCSPHEADER !!};"
    >
    <title>{{ $title ? $title . ' | Xentral' : 'Xentral' }}</title>

    <link rel="stylesheet" href="./themes/{{ $theme }}/css/normalize.min.css?v=6">
    <link rel="stylesheet" href="./themes/{{ $theme }}/css/{!! $COLORCSSFILE !!}?v=7">
    <style>
        :root {
        {!! $COLORCSS !!}
        }
    </style>
    <link rel="stylesheet" href="./css/app.min.css?v=1">
    <link rel="stylesheet" href="./themes/{{ $theme }}/css/styles.css?v=32">
    <link rel="stylesheet" href="./themes/{{ $theme }}/css/resp-menu.css?v=5">

    <script type="text/javascript" src="./js/event.js"></script>
    <script type="text/javascript" src="./js/lib/jquery.min.js"></script>
    <script type="text/javascript" src="{!! $JQUERYMIGRATESRC !!}"></script>
    <script type="text/javascript" src="./js/ajax_001.js?v=14"></script>
    <script type="text/javascript" src="./js/lib/jquery.jeditable.js"></script> <!-- dependency for ckeditor -->
    <script type="text/javascript" src="./js/lib/jquery.loadingOverlay.js"></script> <!-- LoadingOverlay is used -->
    <script type="text/javascript" src="./js/lib/{!! $VUEJS !!}"></script>

    <link rel="stylesheet" type="text/css" href="./js/lib/datatables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="./themes/{{ $theme }}/css/datatables_custom.css?v=3"/>
    <link href="./themes/{{ $theme }}/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="./themes/{{ $theme }}/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" media="screen">
    <script type="text/javascript" src="./js/lib/datatables/datatables.min.js?v=3"></script>
    <script type="text/javascript" src="./js/lib/jquery.dataTables.columnFilter.js"></script>

    <script type="text/javascript" src="./js/lib/jquery.base64.min.js"></script> <!-- is used -->
    <script type="text/javascript" src="./js/lib/jquery.clock.min.js"></script> <!-- is used in stechuhr -->
    <script type="text/javascript" src="./js/lib/Chart.min.js"></script>
    <script type="text/javascript" src="./js/chart-plugins.js?v=2"></script>
    <script type="text/javascript" src="./js/chart-helper.js?v=2"></script>
    <script type="text/javascript" src="./js/textvorlagen.js"></script>
    <script type="text/javascript" src="./js/sidebar.js"></script>

    <link href="./css/bootstrap.min.css?v=4" rel="stylesheet" type="text/css" media="screen">
    <link href="./css/bootstrap-mobile.css?v=2" rel="stylesheet" type="text/css" media="screen">
    <link rel="stylesheet" href="./themes/{{ $theme }}/css/calendar.css?v=3">

    <link rel="apple-touch-icon" sizes="180x180" href="./themes/new/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./themes/new/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./themes/new/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="./themes/new/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="./themes/new/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="./themes/new/images/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#9f00a7">
    <meta name="msapplication-config" content="./themes/new/images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <link type="text/css" href="./css/lib/jquery-ui-1.10.3.custom.css?v=4" rel="Stylesheet"/>
    <link href="./themes/{{ $theme }}/css/colorPicker.css" rel="stylesheet" type="text/css"/>

    <script src="./js/lib/ckeditor4/ckeditor.js"></script>
    <script src="./js/lib/ckeditor4/adapters/jquery.js"></script>
    {!! $CKEDITORJS !!}

    <script type="text/javascript" src="./js/lib/jquery-ui-1.11.4.custom.min.js"></script>
    <script type="text/javascript" src="./js/lib/jquery.ui.touch-punch.js"></script>
    <script type="text/javascript" src="./js/lib/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="./js/lib/jquery.colorPicker.min.js"></script>

    {!! $SCRIPTJAVASCRIPT !!}
    <script type="application/json" id="popupattributes">
        {!! json_encode(['popupwidth' => $popupWidth, 'popupheight' => $popupHeight]) !!}
    </script>

    <script type="application/json" id="calendarattributes">
        {!! json_encode([
            'dayNames' => [__('Sonntag'), __('Montag'), __('Dienstag'),__('Mittwoch'), __('Donnerstag'), __('Freitag'), __('Samstag')],
            'monthNames' => [__('Januar'), __('Februar'), __('März'), __('April'), __('Mai'), __('Juni'),__('Juli'),__('August'), __('September'),__('Oktober'),__('November'),__('Dezember')],
            'today' => __('Heute'),
            'month' => __('Monat'),
            'week' => __('Woche'),
            'day' => __('Tag')
        ]) !!}
    </script>

    <script type="text/JavaScript">
        // Wird benötigt für Textvorlagen
        var lastFocusedElement;
        var lastFocusedType; // [input|textarea|ckeditor]
        // ENDE Textvorlagen

        {!! $JAVASCRIPT !!}

        $(document).ready(function() {

            {!! $AUTOCOMPLETE !!}
            {!! $DATATABLES !!}
            {!! $SPERRMELDUNG !!}
            {!! $JQUERY !!}
            {!! $JQUERYREADY !!}

        });


    </script>

    <script src="./themes/{{ $theme }}/js/scripts.js?v=4"></script>

    {!! $ADDITIONALJAVASCRIPT !!}

    <style>
        {!! $YUICSS !!}
    </style>

    {!! $FINALCSSLINKS !!}
    {!! $MODULEJAVASCRIPTHEAD !!}
    {!! $MODULESTYLESHEET !!}
</head>

<body class="{!! $LAYOUTFIXMARKERCLASS !!}" data-module="{!! $MODULE !!}" data-action="{!! $ACTION !!}">
{!! $SIDEBAR !!}

<div id="main">

    <div id="header" class="clearfix">

        <div class="logo-wrapper">
            <div class="menu-opener">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div class="search-wrapper">
            <div class="search-bar">
                <label for="supersearch-input"></label>
                <input type="text" class="search-input" id="supersearch-input" placeholder="{{ __('Suchen') }}">
            </div>
        </div>

        <ul id="topmenu">
            @if(!empty(\erpAPI::Ioncube_Property('testlizenz')))
                <li id="upgrade-licence">
                    <a href="./index.php?module=appstore&action=buy">
                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.47287 12.0104C2.04566 9.80074 1.66708 6.11981 3.59372 3.46237C5.52036 0.804943 9.13654 0.0202146 11.9914 1.64005" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2.21273 11.9649C1.39377 13.3996 1.11966 14.513 1.58214 14.9761C2.2843 15.6776 4.48124 14.6858 7.02522 12.6684" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.93719 12.1581L7.52014 9.74109L12.8923 4.3689C13.3305 3.93091 13.8797 3.62049 14.481 3.47095L15.863 3.12392C16.0571 3.07558 16.2623 3.1325 16.4037 3.27392C16.5451 3.41534 16.602 3.62054 16.5537 3.8146L16.208 5.19732C16.0578 5.7984 15.7469 6.34731 15.3087 6.78527L9.93719 12.1581Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.51976 9.7409L5.54021 9.08128C5.44619 9.05019 5.37505 8.97252 5.35233 8.87613C5.32961 8.77974 5.35857 8.67847 5.42881 8.60867L6.11882 7.91866C6.7306 7.30697 7.63548 7.09343 8.45619 7.36706L9.53644 7.72625L7.51976 9.7409Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.93713 12.1584L10.5968 14.1386C10.6278 14.2326 10.7055 14.3038 10.8019 14.3265C10.8983 14.3492 10.9996 14.3203 11.0694 14.25L11.7594 13.56C12.3711 12.9482 12.5846 12.0434 12.311 11.2226L11.9518 10.1424L9.93713 12.1584Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ __('Upgrade') }}</span>
                    </a>
                </li>
            @endif
            {!! $ICONBOXESHOOK1 !!}
            <li class="mobile-counter">
                <a href="index.php?module=aufgaben&action=list">
						<span class="icon-box">
							<span class="counter">{!! $ANZAHLAUFGABEN !!}</span>
							<span class="icon">
								<svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"
                                >
									<path d="M16.6641 12.667C16.6641 14.8761 14.8732 16.667 12.6641 16.667C10.4549 16.667 8.66406 14.8761 8.66406 12.667C8.66406 10.4579 10.4549 8.66699 12.6641 8.66699"
                                          stroke="var(--header-icon-color, #76899F)" stroke-linecap="round"
                                          stroke-linejoin="round"
                                    />
									<path d="M16.6641 9.66699L13.0174 13.313C12.9237 13.4068 12.7966 13.4595 12.6641 13.4595C12.5315 13.4595 12.4044 13.4068 12.3107 13.313L11.1641 12.167"
                                          stroke="var(--header-icon-color, #76899F)" stroke-linecap="round"
                                          stroke-linejoin="round"
                                    />
									<path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M5.9974 7.00065C7.5622 7.00065 8.83073 5.73212 8.83073 4.16732C8.83073 2.60251 7.5622 1.33398 5.9974 1.33398C4.43259 1.33398 3.16406 2.60251 3.16406 4.16732C3.16406 5.73212 4.43259 7.00065 5.9974 7.00065Z"
                                          stroke="var(--header-icon-color, #76899F)" stroke-linecap="round"
                                          stroke-linejoin="round"
                                    />
									<path d="M6.66536 12.6669H1.33203C1.33197 10.96 2.2637 9.38936 3.76161 8.57116C5.25951 7.75296 7.08461 7.81778 8.5207 8.74018"
                                          stroke="var(--header-icon-color, #76899F)" stroke-linecap="round"
                                          stroke-linejoin="round"
                                    />
								</svg>
							</span>
						</span>
                </a>
            </li>

            {!! $TELEFONVOR !!}
            <li>
                <a title="{{ __('Rückruf') }}" href="index.php?module=telefonrueckruf&action=list">
						<span class="icon-box">
							<span class="counter">{!! $ANZAHLTELEFON !!}</span>
							<span class="icon">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"
                                >
									<path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M9.89041 14.457L9.89787 14.4614C11.2591 15.328 13.0397 15.1327 14.1808 13.9916L14.6623 13.5101C15.1101 13.0617 15.1101 12.3353 14.6623 11.8869L12.6335 9.85933C12.1851 9.4115 11.4587 9.4115 11.0103 9.85933V9.85933C10.7952 10.0747 10.5033 10.1957 10.199 10.1957C9.89464 10.1957 9.60278 10.0747 9.38771 9.85933L6.14133 6.61233C5.6935 6.16393 5.6935 5.43754 6.14133 4.98914V4.98914C6.35667 4.77407 6.47767 4.4822 6.47767 4.17786C6.47767 3.87351 6.35667 3.58165 6.14133 3.36657L4.11312 1.33588C3.66473 0.888042 2.93833 0.888042 2.48993 1.33588L2.00839 1.81742C0.867543 2.95853 0.672035 4.73879 1.53804 6.1003L1.54302 6.10777C3.76622 9.39883 6.59984 12.2331 9.89041 14.457V14.457Z"
                                          stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"
                                    />
								</svg>

              				</span>
						</span>
                </a>
            </li>
            {!! $TELEFONNACH !!}

            {!! $VORCHATNACHRICHTENBOX !!}
            <li>
                <a title="{{ __('Chat') }}" {!! $CHATLINK ?? '' !!} >
						<span class="icon-box">
							<span class="counter chatbox">{!! $CHATNACHRICHTENBOXCOUNTER ?? '' !!}</span>
							<span class="icon">
								<svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"
                                >
									<path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M16.6654 11.333C16.6654 11.7012 16.3669 11.9997 15.9987 11.9997H8.66536L5.9987 14.6663V11.9997H1.9987C1.63051 11.9997 1.33203 11.7012 1.33203 11.333V1.99967C1.33203 1.63148 1.63051 1.33301 1.9987 1.33301H15.9987C16.3669 1.33301 16.6654 1.63148 16.6654 1.99967V11.333Z"
                                          stroke="var(--header-icon-color, #76899F)" stroke-linecap="round"
                                          stroke-linejoin="round"
                                    />
									<path d="M4.66406 8H13.3307" stroke="var(--header-icon-color, #76899F)"
                                          stroke-linecap="round" stroke-linejoin="round"
                                    />
									<path d="M4.66406 4.66602H13.3307" stroke="var(--header-icon-color, #76899F)"
                                          stroke-linecap="round" stroke-linejoin="round"
                                    />
								</svg>
							</span>
						</span>
                </a>
            </li>
            {!! $NACHCHATNACHRICHTENBOX !!}

            <li id="profile-info-wrapper">
                <div id="profile-info-name">
                    {!! $USERNAME_SHORTENED !!}
                    <svg width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 5L5 -4.37114e-07L0 5L10 5Z" fill="#D9D9D9"/>
                    </svg>
                </div>
            </li>
            <li id="profile-picture-wrapper">
                <div id="profile-picture">
                    <div>
                        {!! $PROFILEPICTURE !!}
                    </div>
                </div>
            </li>
            {!! $ICONBOXESHOOK !!}
        </ul>
        <div id="profile-menu">
            <a title="{{ __('Profil bearbeiten') }}" href="./index.php?module=welcome&action=settings">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.09466 7.16184C6.16554 7.0446 6.2503 6.93561 6.34725 6.83704C6.40907 6.77393 6.43697 6.6869 6.42282 6.60133C6.40867 6.51577 6.35406 6.44132 6.27491 6.39968C5.64096 6.06147 4.92818 5.8827 4.20291 5.88C2.27153 5.88851 0.571775 7.10529 0.0122465 8.87993C-0.014098 8.96466 0.00280169 9.05636 0.0578306 9.12728C0.112859 9.19819 0.19955 9.23998 0.291663 9.24001H5.74466C5.84415 9.24015 5.93685 9.19161 5.99058 9.11123C6.04432 9.03085 6.05167 8.92973 6.01008 8.84297C5.75077 8.30293 5.78234 7.67538 6.09466 7.16184Z" fill="#7D7F81"/>
                    <path d="M4.08272 5.32C5.61301 5.32 6.85356 4.12908 6.85356 2.66C6.85356 1.19092 5.61301 0 4.08272 0C2.55243 0 1.31189 1.19092 1.31189 2.66C1.31189 4.12908 2.55243 5.32 4.08272 5.32Z" fill="#7D7F81"/>
                    <path d="M10.353 10.3774C10.8362 10.3774 11.228 10.0013 11.228 9.53736C11.228 9.07344 10.8362 8.69736 10.353 8.69736C9.86972 8.69736 9.47797 9.07344 9.47797 9.53736C9.47797 10.0013 9.86972 10.3774 10.353 10.3774Z" fill="#7D7F81"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8574 7.58816C14.085 7.96776 14.0284 8.44547 13.7177 8.76624L13.1425 9.36208C13.0481 9.46115 13.0481 9.61301 13.1425 9.71208L13.7183 10.3079C14.029 10.6287 14.0856 11.1064 13.858 11.486C13.6303 11.8656 13.1716 12.0584 12.7266 11.9616L11.9035 11.7785C11.7681 11.7491 11.6322 11.8255 11.5926 11.9532L11.3453 12.7327C11.2138 13.153 10.8107 13.4408 10.3536 13.4408C9.89645 13.4408 9.49341 13.153 9.36193 12.7327L9.11459 11.9532C9.07454 11.8258 8.93897 11.7496 8.80368 11.7785L7.98059 11.9616C7.53563 12.0584 7.07686 11.8656 6.84922 11.486C6.62159 11.1064 6.67824 10.6287 6.98893 10.3079L7.56526 9.71208C7.65966 9.61301 7.65966 9.46115 7.56526 9.36208L6.98893 8.76568C6.67824 8.44491 6.62159 7.9672 6.84922 7.5876C7.07686 7.208 7.53563 7.01514 7.98059 7.112L8.80309 7.29568C8.93822 7.32456 9.07371 7.24866 9.11401 7.12152L9.36134 6.34144C9.49351 5.92178 9.89633 5.6347 10.353 5.6347C10.8097 5.6347 11.2125 5.92178 11.3447 6.34144L11.592 7.12152C11.6321 7.24884 11.7678 7.32484 11.9029 7.29568L12.726 7.11256C13.171 7.0157 13.6297 7.20856 13.8574 7.58816ZM8.60302 9.53734C8.60302 10.4652 9.38652 11.2173 10.353 11.2173C11.3195 11.2173 12.103 10.4652 12.103 9.53734C12.103 8.6095 11.3195 7.85734 10.353 7.85734C9.38652 7.85734 8.60302 8.6095 8.60302 9.53734Z" fill="#7D7F81"/>
                </svg>

                <span>{{ __('Profil bearbeiten') }}</span>
            </a>
            <a target="_blank" title="{{ __('xentral Community') }}" href="https://community.xentral.com">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.00066 10.693C9.48758 10.693 10.693 9.48758 10.693 8.00066C10.693 6.51374 9.48758 5.30835 8.00066 5.30835C6.51374 5.30835 5.30835 6.51374 5.30835 8.00066C5.30835 9.48758 6.51374 10.693 8.00066 10.693Z" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11.6748 13.9597L8.37939 10.6643" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10.6626 8.3811L13.958 11.6758" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2.04077 11.6756L5.33616 8.38025" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.62107 10.6643L4.32568 13.959" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4.32568 2.04077L7.62107 5.33544" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5.33616 7.62021L2.04077 4.32483" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.958 4.32483L10.6626 7.62021" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.37939 5.33544L11.6748 2.04077" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8 15C11.866 15 15 11.866 15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15Z" stroke="#94979E" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <span>{{ __('xentral Community') }}</span>
            </a>
            <a title="{{ __('Abmelden') }}" href="./index.php?module=welcome&action=logout">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.63031 11.375C9.63031 11.0528 9.89148 10.7917 10.2136 10.7917C10.5356 10.7917 10.7967 11.0525 10.797 11.3744V12.5411C10.797 12.8633 10.5358 13.1244 10.2136 13.1244H5.83864V13.7078C5.83866 13.7962 5.79854 13.8799 5.72956 13.9353C5.66056 13.9905 5.57044 14.0119 5.48398 13.9936L0.233977 12.8269C0.100228 12.7973 0.00509078 12.6787 0.00531044 12.5417V1.45834C0.00536462 1.32158 0.100441 1.20322 0.233977 1.17368L5.48398 0.00700995C5.57029 -0.0120843 5.66061 0.00899695 5.72955 0.0643302C5.79849 0.119663 5.83861 0.203277 5.83864 0.291677V0.87501H10.2136C10.5358 0.87501 10.797 1.13618 10.797 1.45834V2.91668C10.797 3.23884 10.5358 3.50001 10.2136 3.50001C9.89148 3.50001 9.63031 3.23884 9.63031 2.91668V2.33334C9.63031 2.17226 9.49973 2.04168 9.33864 2.04168H6.13031C5.96923 2.04168 5.83864 2.17226 5.83864 2.33334V11.6667C5.83864 11.8278 5.96923 11.9583 6.13031 11.9583H9.33864C9.49973 11.9583 9.63031 11.8278 9.63031 11.6667V11.375ZM3.50464 8.16667C3.98789 8.16667 4.37964 7.77492 4.37964 7.29167C4.37964 6.80842 3.98789 6.41667 3.50464 6.41667C3.02139 6.41667 2.62964 6.80842 2.62964 7.29167C2.62964 7.77492 3.02139 8.16667 3.50464 8.16667Z" fill="#7D7F81"/>
                    <path d="M13.1203 6.41666H10.714V5.39583C10.7141 5.18065 10.5956 4.98292 10.4059 4.8814C10.2162 4.77989 9.98596 4.79107 9.80696 4.9105L6.96321 6.80633C6.80095 6.91452 6.70349 7.09664 6.70349 7.29166C6.70349 7.48668 6.80095 7.6688 6.96321 7.777L9.80696 9.67283C9.98596 9.79226 10.2162 9.80344 10.4059 9.70192C10.5956 9.60041 10.7141 9.40268 10.714 9.1875V8.16666H13.1203C13.4329 8.16666 13.7218 7.99989 13.8781 7.72916C14.0344 7.45844 14.0344 7.12489 13.8781 6.85416C13.7218 6.58344 13.4329 6.41666 13.1203 6.41666Z" fill="#7D7F81"/>
                </svg>

                <span>{{ __('Abmelden') }}</span>
            </a>
        </div>
    </div>
    {!! $FIXEDNOTIFICATION !!}


    <div class="menu-wrapper">
        <div class="close-mobile"><span class="close-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 118 118"
                ><path d="M76 60.88l40.41 40.41a5.84 5.84 0 0 1 1.61 3.79 5.77 5.77 0 0 1-1.61 3.76l-7.55 7.55a5.77 5.77 0 0 1-3.76 1.61 5.84 5.84 0 0 1-3.79-1.61L60.88 76a2.6 2.6 0 0 0-3.77 0L16.7 116.39a5.79 5.79 0 0 1-3.76 1.61 5.88 5.88 0 0 1-3.79-1.61l-7.55-7.55a5.81 5.81 0 0 1-1.6-3.76 5.88 5.88 0 0 1 1.6-3.79L42 60.88a2.6 2.6 0 0 0 0-3.77L1.6 16.7A5.82 5.82 0 0 1 0 13a5.92 5.92 0 0 1 1.6-3.8l7.55-7.6A5.92 5.92 0 0 1 12.94 0a5.83 5.83 0 0 1 3.76 1.6L57.11 42a2.6 2.6 0 0 0 3.77 0l40.41-40.4a4.93 4.93 0 0 1 3.79-1.6 5.81 5.81 0 0 1 3.76 1.6l7.55 7.55A5.88 5.88 0 0 1 118 13a5.79 5.79 0 0 1-1.61 3.75L76 57.11a2.6 2.6 0 0 0 0 3.77z"
                       fill="#fff"
                    /></svg></span></div>
        <ul id="mainmenu" class="clearfix menu">
            {!! $NAV !!}
        </ul>

        <div class="clear"></div>

        @if($overviewLink)
            '<a href="{{ $overviewLink }}" title="{{ __('Zur Einstellungsübersicht') }}" id="back-to-overview"></a>'
        @endif

        <div id="current">

            <span class="headline-1">{{ $headLine ?: __($MODUL) }}</span>
            @if($subHeadLine)
                <span class="headline-2">{{ $subHeadLine }}</span>
            @endif
            @if($secondSubHeadLine)
                <span class="headline-3">{{ $secondSubHeadLine }}</span>
            @endif
        </div>

        <div class="clear"></div>

        <div id="submenu-wrapper" class="clearfix">
            <div class="back"><a href="{!! $TABSBACK !!}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="7px" height="12px" viewBox="0 0 131.99 218.57">
                        <path fill="none" stroke="var(--text-color, #929292)" stroke-miterlimit="10" stroke-width="24"
                              d="M120.26 11.65l-97 97.21 97 98.11"
                        />
                    </svg>
                </a></div>
            <div class="new">{!! $TABSADD !!}</div>
            {!! $CURRENTHEADLINES !!}

            <ul id="submenu" class="clearfix menu">
                {!! $TABS !!}
                {!! $TABSFREIGABE !!}
            </ul>

            <div class="buttons">
                <input type="button" value="edit" id="edit">
                <label for="edit">
						<span class="icon">
							<svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"
                            >
								<path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M11.0156 1.90332L13.4905 4.37819L4.7113 13.1574L2.23642 10.6825L11.0156 1.90332Z"
                                      stroke="var(--text-color, #929292)" stroke-width="0.8" stroke-linecap="round"
                                      stroke-linejoin="round"
                                />
								<path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M2.23725 10.6826L1 14.3949L4.71233 13.1577L2.23725 10.6826V10.6826Z"
                                      stroke="var(--text-color, #929292)" stroke-width="0.8" stroke-linecap="round"
                                      stroke-linejoin="round"
                                />
								<path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M13.4901 4.37816L11.0156 1.90366L11.428 1.49125C12.1147 0.828045 13.2062 0.837529 13.8812 1.51256C14.5563 2.1876 14.5657 3.27908 13.9025 3.96575L13.4901 4.37816Z"
                                      stroke="var(--text-color, #929292)" stroke-width="0.8" stroke-linecap="round"
                                      stroke-linejoin="round"
                                />
							</svg>
						</span>
                </label>
                <input type="button" value="print" id="print">
                <label for="print">
						<span class="icon">
							<svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"
                            >
								<path d="M5.08203 12.083H10.332" stroke="var(--text-color, #929292)" stroke-width="0.8"
                                      stroke-linecap="round" stroke-linejoin="round"
                                />
								<path d="M5.08203 10.333H10.332" stroke="var(--text-color, #929292)" stroke-width="0.8"
                                      stroke-linecap="round" stroke-linejoin="round"
                                />
								<path d="M3.91667 10.3343H2.16667C1.52313 10.3324 1.00192 9.81118 1 9.16764V5.66764C1.00192 5.02411 1.52313 4.5029 2.16667 4.50098H13.25C13.8935 4.5029 14.4147 5.02411 14.4167 5.66764V9.16764C14.4147 9.81118 13.8935 10.3324 13.25 10.3343H11.5"
                                      stroke="var(--text-color, #929292)" stroke-width="0.8" stroke-linecap="round"
                                      stroke-linejoin="round"
                                />
								<path d="M3.91797 3.33431V1.00098H9.5098C9.6645 1.00101 9.81285 1.06249 9.92222 1.17189L11.3304 2.58006C11.4398 2.68943 11.5013 2.83778 11.5013 2.99248V3.33431"
                                      stroke="var(--text-color, #929292)" stroke-width="0.8" stroke-linecap="round"
                                      stroke-linejoin="round"
                                />
								<path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M11.5013 13.834C11.5013 14.1562 11.2401 14.4173 10.918 14.4173H4.5013C4.17914 14.4173 3.91797 14.1562 3.91797 13.834V8.58398H11.5013V13.834Z"
                                      stroke="var(--text-color, #929292)" stroke-width="0.8" stroke-linecap="round"
                                      stroke-linejoin="round"
                                />
								<path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M2.7513 6.83366C3.07347 6.83366 3.33464 6.57249 3.33464 6.25033C3.33464 5.92816 3.07347 5.66699 2.7513 5.66699C2.42914 5.66699 2.16797 5.92816 2.16797 6.25033C2.16797 6.57249 2.42914 6.83366 2.7513 6.83366Z"
                                      stroke="var(--text-color, #929292)" stroke-width="0.8" stroke-linecap="round"
                                      stroke-linejoin="round"
                                />
								<path d="M9.16797 1V3.33333H11.5013" stroke="var(--text-color, #929292)"
                                      stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"
                                />
							</svg>
						</span>
                </label>
                <input type="button" value="addfav" id="addfav"
                       data-link="index.php?module={!! $MODULE !!}&action={!! $ACTION !!}&id={!! $ID !!}"
                       data-name="{!! $FAVMODULENAME !!}"
                />
                <label for="addfav">
						<span class="icon">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"
                            >
								<path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M6.7286 0.916539C6.77136 0.802834 6.88012 0.727539 7.0016 0.727539C7.12308 0.727539 7.23184 0.802834 7.2746 0.916539L8.75102 5.10254H12.9207C13.0428 5.10253 13.152 5.1786 13.1943 5.29316C13.2366 5.40772 13.2031 5.5365 13.1103 5.61587L9.62602 8.50454L11.0844 12.8854C11.1244 13.0058 11.0819 13.1383 10.9793 13.2131C10.8767 13.2878 10.7376 13.2876 10.6352 13.2126L7.00102 10.5462L3.3651 13.2126C3.26267 13.2861 3.1246 13.2855 3.02283 13.2111C2.92106 13.1367 2.87864 13.0053 2.91768 12.8854L4.37602 8.50454L0.891182 5.61587C0.798371 5.5365 0.764831 5.40772 0.807139 5.29316C0.849448 5.1786 0.958645 5.10253 1.08077 5.10254H5.25102L6.7286 0.916539Z"
                                      stroke="var(--text-color, #929292)" stroke-width="0.8" stroke-linecap="round"
                                      stroke-linejoin="round"
                                />
							</svg>
						</span>
                </label>
                {!! $TABSPRINT !!}
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <div id="page_container" class="{!! $MODULE !!}{!! $ACTION !!}">
        @yield('content')
        {!! $LEGACYTOOLTIP !!}
    </div>
    {!! $DUNNINGASSISTANT !!}
</div>

{!! $JSSCRIPTS !!}
<script type="text/javascript">

</script>
<div id="chatpopup">
    <div id="chatpopupcontent">
    </div>
</div>
{!! $BODYENDE !!}
{!! $MODULEJAVASCRIPTBODY !!}

@yield('additional_scripts')

<script type="text/javascript" src="./js/lib/datatables/datatables-helper.js"></script>
<script type="text/javascript" src="./js/download-spooler.js"></script>
<script type="text/javascript" src="./js/stickybutton.js"></script>
<script type="text/javascript" src="./js/lockscreen.js"></script>
<script type="text/javascript" src="./js/filter.js"></script>
<script type="text/javascript" src="./js/confirm-popup.js"></script>
<script type="text/javascript" src="./js/ajax_end.js?v=9"></script>
</body>
</html>
