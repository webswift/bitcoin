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
                                <table id="exchange_list" class="table coin-table">
                                    <thead>
                                        <tr>
                                            <th>Market</th>
                                            <th>Exchange</th>
                                            <th>Price</th>
                                            <th>Volumn (24h)</th>
                                            <th>%Vol</th>
                                            <th>Date Added</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($exchange_data as $key => $ex) {  
                                        } ?>
                                        
                                        <?php 
                                        $column = array_column($exchange_data, 'volume');
                                        $column = array_filter($column);
                                        $sum = array_sum($column);
                                        ?>
                                        <?php foreach ($exchange_data as $key => $ex) {
                                            if(file_exists('./assets/images/currency-svg/'.strtolower($ex['from_coin']).'.svg')){
                                                $image = base_url().'assets/images/currency-svg/'.strtolower($ex['from_coin']).'.svg';
                                            }
                                            elseif(file_exists('./assets/images/currency-png/'.strtolower($ex['from_coin']).'.png')){
                                                $image = base_url().'assets/images/currency-png/'.strtolower($ex['from_coin']).'.png';
                                            }
                                        ?>
                                            <tr>
                                                <td><img class="coin-single-img" src="<?=$image?>"> <?=$ex['pair']?></td>
                                                <td style="text-transform: capitalize;"><?=$ex['exchange_name']?></td>
                                                <td><?=isset($ex['price']) ? '$'.number_format($ex['price'],2) : '0'?></td>
                                                <td><?=$ex['volume']!='' ? '$'.number_format($ex['volume'],2) : '0'?></td>
                                                <?php
                                                if($ex['volume'] != '0') {
                                                    $volumn = (float)$ex['volume']/(float)$sum;
                                                    $volumn = $volumn*100;
                                                } else {
                                                    $volumn = 0;
                                                }
                                                ?>
                                                <td><?=number_format($volumn,2)?>%</td>
                                                <td><?=date('M d,Y',strtotime($ex['date_added']))?></td>
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
    var table = $('#exchange_list').DataTable({
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
        "autoWidth": false,
        "order": [[ 3, "desc" ]],  
        // "columnDefs": [ { orderable: false, targets: [1,3,4,5,6,7] } ],
    });
});
</script>