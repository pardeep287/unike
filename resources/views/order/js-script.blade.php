<script type="text/javascript">
    $('select.ajaxManual').on('change', function(event)
    {
        var val = $(this).val();
        var target = $(this).data("target");
        var route = $(this).data("route");
        var isVal = $(this).data("value");
        if (val != '') {
            if (!$(this).data("route")) {
                alert('Error: route attribute not defined. Please define route attribute.');
                return false;
            }

            $.ajax({
                type: "GET",
                url : route + '/' + val,
                beforeSend: function() {
                    //$(".backDrop").fadeIn( 100, "linear" );
                    //$(".loader").fadeIn( 100, "linear" );
                },
                success:function(data, textStatus, jqXHR) {
                    var obj = jQuery.parseJSON( data );
                    $('.hsn_code').html(obj.hsn_code);
                    $('.unit').html(obj.unit);
                    $('.gst').html(obj.gst);
                    $('.tax_id').val(obj.gst_id);
                    $('.price_rate').val(obj.price_rate);
                    $('.manual_price').val(obj.price_rate);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('You have '+errorThrown+' request cannot processing..');
                }
            });
            return false;
            event.preventDefault(); //STOP default action
            event.unbind(); //unbind. to stop multiple form submit.
        }
    });
    $('select#account').on('change', function(event){
        var val = $(this).val();
        var route = $(this).data("route");
        if (val != '') {
            if (!$(this).data("route")) {
                alert('Error: route attribute not defined. Please define route attribute.');
                return false;
            }

            $.ajax({
                type: "GET",
                url : route + '/' + val,
                beforeSend: function() {
                    //$(".backDrop").fadeIn( 100, "linear" );
                    //$(".loader").fadeIn( 100, "linear" );
                },
                success:function(data, textStatus, jqXHR) {
                    console.log(data);
                    var obj = jQuery.parseJSON( data );
                    $('select#sale').find('option:selected').removeAttr( "selected");
                    $('select#sale').val(obj.saleType).attr("selected");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('You have '+errorThrown+' request cannot processing..');
                }
            });
            return false;
            event.preventDefault(); //STOP default action
            event.unbind(); //unbind. to stop multiple form submit.
        }
    });
</script>