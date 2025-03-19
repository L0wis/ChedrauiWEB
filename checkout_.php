<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chedraui</title>

    <script src="https://www.paypal.com/sdk/js?client-id=AfbKCkRuw0YL3NxqLfSYK-MlAp9YPE0NneWmsRyJbxjW6IzQOaKO5Tl-PHHStv0M6o0QtqknQv55dfAB&currency=MXN"></script>
    
</head>
<body>

<div id="paypal-button-container"></div>

<script>
    paypal.Buttons({
        style:{
            color: 'blue',
            shape: 'pill',
            label: 'pay'
        },
        createOrder:function(data, actions){
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value:100
                    }
                }]
            });
        },

        onApprove: function(data, actions){
            actions.order.capture().then(function (detalles){
                window.location.href="completado.html"
            });
        },

        onCancel:function(data){
            alert("Pago cancelado");
            console.log(data);
        }
    }).render('#paypal-button-container');
</script>

<?php
/*echo "";
include "menu.php"; */

?>
    
</body>
</html>
