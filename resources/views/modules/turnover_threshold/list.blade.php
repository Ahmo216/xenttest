@extends('layouts.logged-in')

@section('content')
<div id="tabs">
  <ul>
    <li><a href="#tabs-1"></a></li>
  </ul>
<div id="tabs-1">
  <form method="post">
    {!! $MESSAGE ?? '' !!}

    <div class='row'>
      <div class='row-height'>
        <div class='col-xs-12 col-md-10 col-md-height'>
          <div class='inside_white inside-full-height'>
            <fieldset class="white">
              <legend></legend>
              {!! $listTable !!}
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
                             id="newturnoverthreshold"
                             value="✚ {{ __('Neue Lieferschwelle') }}"
                /></center>
            </fieldset>

          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- tab view schließen -->
</div>
<form method="post">
  <div id="editLieferschwelle" data-deletemessage="{{ __('Wirklich löschen?') }}" style="display:none;" title="{{ __('Bearbeiten') }}">
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <input type="hidden" id="e_id">
            <fieldset>
              <legend>{{ __('Lieferschwelle') }}</legend>
              <table>
                <tr>
                  <td width="140"><label for="e_empfaengerland">{{ __('Empfängerland') }}:</label></td>
                  <td><select name="e_empfaengerland" id="e_empfaengerland">
                      @foreach($countries as $countryCode => $countryLabel)
                      <option {{ $countrySelected === $countryCode ? ' selected' : '' }} value="{{ $countryCode }}">{{ $countryLabel }}</option>
                      @endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><label for="e_storage">{{ __('Lager') }}:</label></td>
                  <td>
                    <select name="e_storage" id="e_storage">
                      <option value="0">{{ __('Kein Lager') }}</option>
                      <option value="1">{{ __('Lager') }}</option>
                      <option value="2">{{ __('Hauptlager') }}</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><label for="e_lieferschwelleeur">{{ __('Lieferschwelle') }}:</label></td>
                  <td><input type="text" name="e_lieferschwelleeur" id="e_lieferschwelleeur" size="30"></td>
                </tr>
                <tr>
                  <td><label for="e_ustid">{{ __('Ust-ID') }}:</label></td>
                  <td><input type="text" name="e_ustid" id="e_ustid" size="30"></td>
                </tr>
                <tr>
                  <td><label for="e_steuersatznormal">{{ __('Steuersatz normal') }}:</label></td>
                  <td><input type="text" name="e_steuersatznormal" id="e_steuersatznormal"></td>
                </tr>
                <tr>
                  <td><label for="e_steuersatzermaessigt">{{ __('Steuersatz ermäßigt') }}:</label></td>
                  <td><input type="text" name="e_steuersatzermaessigt" id="e_steuersatzermaessigt"></td>
                </tr>
                <tr>
                  <td><label for="e_steuersatzspezial">{{ __('Steuersatz spezial') }}:</label></td>
                  <td><input type="text" name="e_steuersatzspezial" id="e_steuersatzspezial">&nbsp;<label for="e_spezialursprungsland">{{ __('in Ursprungsland') }}:</label>
                    &nbsp;<input type="text" name="e_spezialursprungsland" id="e_spezialursprungsland"></td>
                </tr>
                <tr>
                  <td><label for="e_ueberschreitung">{{ __('Überschreitung ab') }}:</label></label></td>
                  <td><input data-errormessage="{{ __('Bitte geben Sie ein Überschreiungsdatum an') }}"
                             type="text" name="e_ueberschreitung" id="e_ueberschreitung"></td>
                </tr>
                <tr>
                  <td><label for="e_umsatz">{{ __('Aktueller Umsatz') }}:</label></td>
                  <td><input type="text" name="e_umsatz" id="e_umsatz"></td>
                </tr>
                <tr>
                  <td><label for="e_currency">{{ __('Währung') }}:</label></td>
                  <td>
                    <select name="e_currency" id="e_currency">
                      @foreach($currencies as $currencyCode)
                      <option value="{{ $currencyCode }}">{{ $currencyCode }}</option>
                      @endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><label for="e_current_revenue_in_eur">{{ __('Aktueller Umsatz in EUR') }}:</label></td>
                  <td><input type="text" name="e_current_revenue_in_eur" id="e_current_revenue_in_eur"></td>
                </tr>
                <tr>
                  <td><label for="e_preiseanpassen">{{ __('Netto Preise anpassen') }}:</label></td>
                  <td><input type="checkbox" name="e_preiseanpassen" id="e_preiseanpassen"><i>{{ __('Beim Import vom Online-Shop') }}</i></td>
                </tr>
                <tr class="tractive">
                  <td><label for="e_verwenden">{{ __('Lieferschwelle aktiv') }}:</label></td>
                  <td><input type="checkbox" name="e_verwenden" id="e_verwenden"></td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <fieldset>
              <legend>{{ __('Erlöskonten') }}</legend>
              <table>
                <tr>
                  <td width="140"><label for="e_erloeskontonormal">{{ __('Erlöskonto normal') }}:</label></td>
                  <td><input type="text" name="e_erloeskontonormal" id="e_erloeskontonormal"></td>
                </tr>
                <tr>
                  <td><label for="e_erloeskontoermaessigt">{{ __('Erlöskonto ermäßigt') }}:</label></td>
                  <td><input type="text" name="e_erloeskontoermaessigt" id="e_erloeskontoermaessigt"></td>
                </tr>
                <tr>
                  <td><label for="e_erloeskontobefreit">{{ __('Erlöskonto befreit') }}:</label></td>
                  <td><input type="text" name="e_erloeskontobefreit" id="e_erloeskontobefreit"></td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

