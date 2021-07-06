<script type="application/javascript">
  $(document).ready(function() {
    if($('input[name="trackingsubmit"]').length > 0 && $('input[name="tracking"]').length > 0
    && $('input[name="tracking"]').val()+'' != '')
    {
      $('input[name="trackingsubmit"]').trigger('click');
      $('#tabs').loadingOverlay('show');
    }
  });
</script>