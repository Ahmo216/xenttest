<script type="application/javascript">
  $(document).ready(function() {
    if($('input[name="drucken"]').length > 0)
    {
      $('input[name="drucken"]').trigger('click');
      $('#tabs').loadingOverlay('show');
    }
  });
</script>