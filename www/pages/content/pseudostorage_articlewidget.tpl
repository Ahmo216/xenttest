<!--<tr>
    <td>
        <label for="pseudostorage_formula">{|Pseudolager Regeln|}:</label>
    </td>
    <td rowspan="2">
        <textarea id="pseudostorage_formula" name="pseudostorage_formula">[PSEUDOSTORAGE_FORMULA]</textarea>
    </td>
</tr>-->
<tr>
    <td>
        {|Vorschau:|}&nbsp;<span id="pseudostorage_formula_preview"></span>
    </td>
</tr>
<script type="application/javascript">
    $(document).ready(function () {
        $('#pseudolager').on('change', function () {
            $.ajax({
                url: 'index.php?module=pseudostorage&action=list&cmd=getarticleformulapreview&id=[ID]',
                type: 'POST',
                dataType: 'json',
                data: { formula: $(this).val()},
                success: function(data) {
                    if(typeof data.preview != 'undefined') {
                        $('#pseudostorage_formula_preview').html(data.preview);
                    }
                }
            });
        });
        $('#pseudolager').trigger('change');
    });
</script>
