<script>
  $(document).ready(function() {
    $('div.seriennummernwidget').hide();
  });
    
  function seriennummernassistenclick()
  {
    $('div.seriennummernwidget').show(200);
  }
  
  function CalcNextSeriennummer(nummer)
  {
    nummer = nummer + '';
    let len = nummer.length;
    if(len < 1)return '1';
    let pos = len;
    let i = len;
    for(i = len - 1; i >= 0; i--){
      let n = nummer.charCodeAt(i);
      if(n > 58 || n < 48)
      {
        break;
      }else pos = i;
    }
    let ret = '';
    if(pos > 0)
    {
      ret = nummer.substr(0, pos);  
    }
    if(pos == len)return nummer+'1';
    let rest = nummer.substr(pos);
    let restlength = rest.length;
    rest = (parseInt(rest)+1)+'';
    let restlength2 = rest.length;
    if(restlength2 < restlength)
    {
      for(i = 0; i < restlength - restlength2; i++)ret = ret + '0';
    }
    return ret+rest;
  }
    
  function seriennummerngenerieren()
  {
    let menge = parseInt($('#seriennummernwidgetanzahl').val());
    let start = $('#seriennummernwidgetstart').val();
    if(start+'' == '')return;
    if(isNaN(menge) || menge < 1)menge = 1;
    let startid = 1;
    let el = null;
    while(true)
    {
      el = $('#[ELEMENTPREFIX]'+startid);
      if(el == null || el.length == 0)return;
      if($(el).val()+'' == '')
      {
        $(el).val(start);
        start = CalcNextSeriennummer(start);
        menge--;
        if(menge < 0)return;
      }
      startid++;
      if(startid > 1000)return;
    }
  }
</script>
<input type="button" value="{|Assistent verwenden|}" onclick="seriennummernassistenclick();" >
<div class="seriennummernwidget">
  <table>
    <tr>
      <td>{|Start|}:</td><td><input type="text" id="seriennummernwidgetstart"></td>
    </tr><tr>
      <td>{|Anzahl forlaufende Nr.|}:</td><td><input type="text" id="seriennummernwidgetanzahl"></td>
    </tr><tr>
      <td></td><td><input type="button" class="btnGreen" value="{|Seriennummern jetzt erstellen|}" onclick="seriennummerngenerieren();" /></td>
    </tr>
  </table>  
</div>
