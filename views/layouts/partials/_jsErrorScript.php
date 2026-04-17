<?php
    $baseUrl = \yii\helpers\BaseUrl::base();
?>
<script>
    function send_error_to_server(msg, url, line)
    {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url:  '<?=$baseUrl?>/site-alert/add-js-error',
            type: 'post',
            dataType: 'json',
            data: {
                msg: msg,
                url: url,
                line: line,
                _csrf : csrfToken,
            },
            error: function(){} //we need it to disable errorManager in keywords.js
        });        
    }
    window.onerror = function(msg, url, line) {
        send_error_to_server(msg, url, line);
    };
</script>