<!-- WAWICORE -->
<style type="text/css">
	#drop-files {
		width: 100%;
		height: calc(100vh - 100px);
		background: rgba(0, 0, 0, 0.1);
		border: 4px dashed rgba(0, 0, 0, 0.2);
		padding: 40px 0 0 0;
		text-align: center;
		font-size: 2em;
		float: left;
		font-weight: bold;
		margin: 10px 20px 20px 0;
	}
</style>
<div><input type="file" id="upload" name="upload" /></div>
<div id="drop-files" ondragover="return false">
	{|Dateien hier einf&uuml;gen|}
</div>
<script type="application/javascript">

  $(document).ready(function() {

    jQuery.event.props.push('dataTransfer');

    var z = -40;
    // The number of images to display
    var maxFiles = 5;
    var errMessage = 0;

    // Get all of the data URIs and put them in an array
    var dataArray = [];

    // Bind the drop event to the dropzone.
    $('#drop-files').bind('drop', function (e) {

      // Stop the default action, which is to redirect the page
      // To the dropped file

      var files = e.dataTransfer.files;


      $.each(files, function (index, file) {
        var isimg = false;
        if (files[index].type.match('image.*')) {
          isimg = true;
        }
        var fileReader = new FileReader();


        // When the filereader loads initiate a function
        fileReader.onload = (function (file) {
          //alert('x');
          return function (e) {

            // Push the data URI into an array
            dataArray.push({name : file.name, value : this.result});

            // Move each image 40 more pixels across
            z = z + 40;


            // Just some grammatical adjustments
            if (dataArray.length == 1) {
              //$('#upload-button span').html("1 file to be uploaded");
            } else {
              //$('#upload-button span').html(dataArray.length+" files to be uploaded");
            }
            // Place extra files in a list
            var vorschau = '';
            var image = this.result;
            if (isimg) {
              // Place the image inside the dropzone
              //$('#dropped-files').append('<div class="image" style="left: '+z+'px; background: url('+image+'); background-size: cover;"> </div>');

              vorschau = '<span class="image" style="float:right;padding:0;margin:0;height:40px;width:40px;display:inline-block;position:relative;max-width:40px;max-height:40px; background: url(' + image + '); background-size: cover;"></span>';
            }
            else {
              $.ajax({
                url: 'index.php?module=verbindlichkeit&action=edit&id=[ID]&cmd=settmpfile',
                type: 'POST',
                dataType: 'json',
                data: {datei: this.result,name:file.name},
                success: function(data) {
                  $('#vorschauifr').show();
                  $('#drop-files').hide();
                  $('#vorschauifr').attr("src", $('#vorschauifr').attr("src"));
                },
                beforeSend: function() {

                }
              });




              vorschau = '';
              //$('#extra-files .number').html('+'+($('#file-list li').length + 1));
              // Show the extra files dialogue
              //$('#extra-files').show();

              // Start adding the file name to the file list
              //$('#extra-files #file-list ul').append('<li>'+file.name+'</li>');

            }
            //$('.stddownload').hide();
            //$('#trdatei').before('<tr><td>Datei '+vorschau+'</td><td class="tddateiname"><input type="hidden" name="dateiv[]" value="'+image+'" /><input type="hidden" name="dateiname[]" value="'+file.name+'" />'+file.name+'</td><td>Titel: <input type="text" name="dateititel[]" /></td><td><select name="dateistichwort[]">'+$('#stichwort').html()+'</select></td></tr>')
          };

        })(files[index]);
        fileReader.readAsDataURL(file);

      });


    });

    $('#drop-files').bind('dragenter', function() {
      $(this).css({'box-shadow' : 'inset 0px 0px 20px rgba(0, 0, 0, 0.1)', 'border' : '4px dashed #bb2b2b'});
      return false;
    });

    $('#drop-files').bind('drop', function() {
      $(this).css({'box-shadow' : 'none', 'border' : '4px dashed rgba(0,0,0,0.2)'});
      return false;
    });
  });

</script>