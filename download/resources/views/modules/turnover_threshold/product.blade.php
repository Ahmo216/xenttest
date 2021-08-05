@extends('layouts.logged-in')

@section('content')
<div id="tabs">
  <ul>
    <li><a href="#tabs-1"></a></li>
  </ul>
  <!-- ende gehort zu tabview -->

  <!-- erstes tab -->
  <div id="tabs-1">
    <form method="post">
      {!! $MESSAGE ?? '' !!}
      <div class='row'>
        <div class='row-height'>
          <div class='col-xs-12 col-md-10 col-md-height'>
            <div class='inside_white inside-full-height'>
              <div class="filter-block filter-inline">
                <div class="filter-title">{{ __('Filter') }}</div>
                <ul class="filter-list">
                  <li class="filter-item">
                    <label for="notusable" class="switch">
                      <input type="checkbox" id="notusable">
                      <span class="slider round"></span>
                    </label>
                    <label for="notusable">{{ __('nicht anwendbare') }}</label>
                  </li>
                </ul>
              </div>
              <fieldset class="white">
                <legend></legend>
                {!! $productTable !!}
              </fieldset>
            </div>
          </div>
          <div class='col-xs-12 col-md-2 col-md-height'>
            <div class='inside inside-full-height'>
              <fieldset>
                <legend>{{ __('Aktionen') }}</legend>
                <center>
                  <input class="btnGreenNew"
                         type="button"
                         name="newproduct"
                         id="newproduct"
                         value="✚ {{__('Neuer Artikel') }}"
                          />
                </center>
              </fieldset>

            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- tab view schließen -->
</div>

<div id="popupArticle" data-deletemessage="{{ __('Wirklich löschen?') }}" style="display:none;" title="{{ __('Bearbeiten') }}">
  <form method="post">
    <input type="hidden" id="e_id">
    <fieldset>
      <legend>{{ __('Lieferschwelle Artikel') }}</legend>
      <table>
        <tr>
          <td width="140"><label for="e_artikel">{{ __('Artikel') }}:</label></td>
          <td><input type="text" name="e_artikel" id="e_artikel" size="40"></td>
        </tr>
        <tr>
          <td><label for="e_artempfaengerland">{{ __('Empfängerland') }}:</label></td>
          <td><select name="e_artempfaengerland" id="e_artempfaengerland">
              @foreach($countries as $countryCode => $countryLabel)
              <option value="{{ $countryCode }}">{{ $countryLabel }}</option>
              @endforeach
            </select>
          </td>
        </tr>
        <tr>
          <td><label for="e_artsteuersatz">{{ __('Steuersatz') }}:</label></td>
          <td><input type="text" name="e_artsteuersatz" id="e_artsteuersatz"></td>
        </tr>
        <tr>
          <td><label for="e_bemerkung">{{ __('Bemerkung') }}:</label></td>
          <td><input type="text" name="e_bemerkung" id="e_bemerkung" size="40"></td>
        </tr>
        <tr>
          <td width="140"><label for="e_revenue_account">{{ __('Erlöskonto') }}:</label></td>
          <td><input type="text" name="e_revenue_account" id="e_revenue_account"></td>
        </tr>
        <tr>
          <td><label for="e_aktiv">{{ __('Aktiv') }}:</label></td>
          <td><input type="checkbox" name="e_aktiv" id="e_aktiv"></td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
@endsection
