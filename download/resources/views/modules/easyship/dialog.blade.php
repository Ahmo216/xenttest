<br><br>
<table id="paketmarketab" align="center">
    <tr>
        <td align="center">
            <br>
            <form action="" method="post">
                {!! $error !!}
                <h1>{{ __('Paketmarken Drucker für :carrier', ['carrier' => $carrierName]) }}</h1>
                <br>
                <b>{{ __('Empfänger') }}</b>
                <br>
                <br>
                <table>
                    <tr>
                        <td>
                            <table style="float:left;">
                                <tr>
                                    <td>{{ __('Name') }}:</td>
                                    <td><input type="text" size="36" value="{{ $name }}" name="name" id="name">
                                        <script type="text/javascript">document.getElementById('name').focus(); </script>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Name 2') }}:</td>
                                    <td><input type="text" size="36" value="{{ $name2 }}" name="name2"></td>
                                </tr>
                                <tr>
                                    <td>{{ __('Name 3') }}:</td>
                                    <td><input type="text" size="36" value="{{ $name3 }}" name="name3"></td>
                                </tr>

                                <tr>
                                    <td>{{ __('Land') }}:</td>
                                    <td>{!! $countrySelect !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('PLZ/Ort') }}:</td>
                                    <td><input type="text" name="plz" size="5" value="{{ $zip }}">&nbsp;<input
                                                type="text" size="30" name="ort" value="{{ $city }}"></td>
                                </tr>
                                <tr>
                                    <td>{{ __('Strasse/Hausnummer') }}:</td>
                                    <td><input type="text" size="30" value="{{ $street }}" name="strasse">&nbsp;<input
                                                type="text" size="5" name="hausnummer" value="{{ $streetNumber }}"></td>
                                </tr>

                                <tr>
                                    <td>{{ __('E-Mail') }}:</td>
                                    <td><input type="text" size="36" value="{{ $email }}" name="email"></td>
                                </tr>
                                <tr>
                                    <td>{{ __('Telefon') }}:</td>
                                    <td><input type="text" size="36" value="{{ $telephone }}" name="telefon"></td>
                                </tr>

								<input type="hidden" name="versandfirma" value="{{ $chosenProduct }}"/>

                                @if($showExportElements)
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <tr id="us-states">
                                    <td>{{ __('Bundesland') }}:</td>
                                    <td>{!! $usStateSelect !!}</td>
                                </tr>
                                <tr id="states">
                                    <td>{{ __('Bundesland') }}:</td>
                                    <td><input type="text" size="36" value="{{ $state }}" name="state"></td>
                                </tr>
                                <tr>
                                    <td>{{ __('Rechnungsnummer') }}:</td>
                                    <td><input type="text" size="36" value="{{ $invoiceNumber }}"
                                               name="rechnungsnummer"></td>
                                </tr>
                                <tr>
                                    <td>{{ __('Kategorie') }}:</td>
                                    <td><select name="category">
                                                @foreach($categories as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                        </select></td>
                                </tr>
                                @endif

							<tr>
								<td nowrap>{{ __('Extra Versicherung') }}:</td>
                                    <td><input type="checkbox" name="versichert" value="1" {{ $insuranceChecked ? 'checked' : '' }} /></td>
							</tr>
						  	<tr class="versicherung" style="display:none;">
								<td>{{ __('Versicherungssumme') }}:</td>
								<td><input type="text" size="10" id="versicherungssumme" name="versicherungssumme" value="{{ $insuranceValue }}" /></td>
							</tr>
                            </table>

                            <table style="float:right;">
                                {!! $weightField !!}
                                <tr>
                                    <td>{{ __('Höhe (in cm)') }}:</td>
                                    <td>
                                        <input type="text" size="10" value="{{ $height }}" name="height">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Breite (in cm)') }}:</td>
                                    <td>
                                        <input type="text" size="10" value="{{ $width }}" name="width">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Lange (in cm)') }}:</td>
                                    <td>
                                        <input type="text" size="10" value="{{ $length }}" name="length">
                                    </td>
                                </tr>
                            </table>

                            <div style="clear:both"></div>
                            <br><br>
                            <center><input class="btnGreen" type="submit" value="{{ __('Paketmarke drucken') }}"
                                           name="drucken">&nbsp;
                                {!! $trackingHtml !!}
                                &nbsp;<input type="button" value="{{ __('Andere Versandart auswählen') }}"
                                             onclick="window.location.href='index.php?module=versanderzeugen&action=wechsel&id={{ $id }}'"
                                             name="anders">&nbsp;
                                </center>
                        </td>
                    </tr>
                </table>
            </form>

            <br><br>
        </td>
    </tr>
</table>
