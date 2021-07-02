<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">{|Scannen|}</a></li>
		<li><a href="#tabs-2">{|Protokoll|}</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">

		<div class="[MESSAGECLASS]" id="messagediv">[MESSAGETEXT]</div>


		<form method="post" onsubmit="ReceiptProtocolEditSave(event); return false;">
			<table>
				<tr>
					<td width="100">{|Bitte scannen|}:</td>
					<td><input type="text" name="receiptprotocol_input" id="receiptprotocol_input" size="30"></td>
				</tr>
			</table>
		</form>

		<table>
			<tr>
				<td>{|Kunde|}:</td>
				<td id="customer">-</td>
			</tr>
			<tr>
				<td>{|Auftrag|}:</td>
				<td id="order">-</td>
			</tr>
			<tr>
				<td width="100">{|Protokolleintrag|}:</td>
				<td id="protocol">-</td>
			</tr>
			<tr>
				<td width="100">{|Benutzer|}:</td>
				<td id="user">[NAMEOFUSER]</td>
			</tr>
	
		</table>

		[TAB1]

		[TAB1NEXT]
	</div>

	<div id="tabs-2">
		[TAB2]
		[TAB2NEXT]
	</div>

	<!-- tab view schließen -->
</div>


<script type="text/javascript">
    var order = '';
    var orderId = '';
		var protocol = '';
		var protocolId = '';
		$(document).ready(function() {
        $('#receiptprotocol_input').focus();
    });

    function ReceiptProtocolEditSave(event) {
        messagediv = document.getElementById('messagediv');
        event.preventDefault();

        $.ajax({
            url: 'index.php?module=receiptprotocolitems&action=list&cmd=scan',
            data: {
                //Alle Felder die fürs editieren vorhanden sind
                input: $('#receiptprotocol_input').val()
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                App.loading.close();
                $('#receiptprotocol_input').val('');
                if(!order){
                    document.getElementById("customer").innerHTML = '-';
                    document.getElementById("order").innerHTML = '-';
                }
                if(!protocol){
                    document.getElementById("protocol").innerHTML = '-';
                }

                if(data.order){
                    document.getElementById("customer").innerHTML = data.customer;
                    document.getElementById("order").innerHTML = data.order;
              			order = data.order;
              			orderId = data.orderId;
                }else{
                    document.getElementById("protocol").innerHTML = data.protocol;
                    protocol = data.protocol;
                    protocolId = data.protocolId;
                }

                if(protocol && order){
                    WriteIntoProtocol(orderId, protocolId);
                }
            }
        });
    }

    function WriteIntoProtocol(orderId, protocolId) {
        $.ajax({
            url: 'index.php?module=receiptprotocolitems&action=list&cmd=write',
            data: {
							orderId:orderId,
							protocolId:protocolId
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                if(data.status == 1){
                    protocol = '';
                    protocolId = '';
                    order = '';
                    orderId = '';
                    document.getElementById("customer").innerHTML = '-';
                    document.getElementById("order").innerHTML = '-';
                    document.getElementById("protocol").innerHTML = '-';
                    document.getElementById("messagediv").innerHTML = data.statusText;
                    document.getElementById("messagediv").className = "info";
                    updateLiveTable();
                }else{
                    document.getElementById("messagediv").innerHTML = data.statusText;
                    document.getElementById("messagediv").className = "error";
                }

            }
        });
    }

    function updateLiveTable(i) {
        var oTableL = $('#receiptprotocol_scan_protocol').dataTable();
        var tmp = $('.dataTables_filter input[type=search]').val();
        oTableL.fnFilter('%');
        oTableL.fnFilter(tmp);
    }

</script>
