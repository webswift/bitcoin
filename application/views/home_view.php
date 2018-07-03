<!DOCTYPE html>
<html>
<title>Home</title>
<head>
    <!-- CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url('assets/css/jquery.dataTables.min.css')?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <link rel="stylesheet" href="<?=base_url('assets/css/custom.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>">
    <!-- CSS -->
    <!-- Script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <script src="<?=base_url('assets/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?=base_url('assets/js/jquery.sparkline.min.js')?>"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <!-- Script -->
</head>
<body>

    <div class="content-wrapper container-fluid">
        <section class="content">
            <div class="container">
                <div class="row m-t-40">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="box-body">
                                <table id="coin_list" class="table coin-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Exchange</th>
                                            <th>Price</th>
                                            <th>Volume (24h)</th>
                                            <th>1hr</th>
                                            <th>24hr</th>
                                            <th>Weekly</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($exchange_list as $key => $list) { ?>
                                            <tr>
                                                <td><?=$list['id']?></td>
                                                <td><a href="<?=base_url()?>exchange/<?=$list['ex_id']?>"><img class="coin-img" src="<?=base_url()?>assets/images/icon/<?=$list['name']?>.png"></a></td>
                                                <td>$ <?=number_format($list['price'],2)?></td>
                                                <td>$ <?=number_format($list['volume'],2)?></td>
                                                <?php 
                                                $h1 = (float)$list['price']-(float)$list['1h'];
                                                $h1 = $h1/(float)$list['price'];
                                                $h1 = $h1*100;
                                                ?>
                                                <td style="color: <?=$h1 >= 0 ?'green':'red'?>"><?=number_format($h1,2)?>%</td>
                                                <td style="color: <?=$list['24h'] >= 0 ?'green':'red'?>"><?=number_format($list['24h'],2)?>%</td>
                                                <td><span class="sparkliness1"><?=$list['weekly']?></span></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
<script type="text/javascript">
$( document ).ready(function() {
    var table = $('#coin_list').DataTable({
        "dom": '<<t>p>',
        "processing": true, 
        // "serverSide": true, 
        /*"ajax": {
            "url": "<?=base_url()?>home/coin_list",
            "type": "POST",
            "data": function ( data ) {
                data.category = $('#category').val();
            },
        },*/
        "autoWidth": false
        // "order": [[ 0, "asc" ]],  
        // "columnDefs": [ { orderable: false, targets: [1,3,4,5,6,7] } ],
    });
    $('.sparkliness1').sparkline('html', { lineWidth: 1.2, disableInteraction: true, spotColor: false, minSpotColor: false, maxSpotColor: false, width: 80, lineColor: '#00940b', height: 20, fillColor: 'rgba(74,185,138,0.3)' });
    $('.sparkliness2').sparkline('html', { lineWidth: 1.2, disableInteraction: true, spotColor: false, minSpotColor: false, maxSpotColor: false, width: 80, lineColor: '#ef0000', height: 20, fillColor: 'rgba(237,77,95,0.3)' });
});
</script>