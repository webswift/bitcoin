<?php
require_once('./application/libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class V1 extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_data($_REQUEST);
    }

    function filterarray($array, $key, $value)
    {
        $results = array();
        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }
            foreach ($array as $subarray) {
                $results = array_merge($results, $this->filterarray($subarray, $key, $value));
            }
        }
        return $results;
    }

    public function home_get()
    {
        $mainarray = [];
        $coinsprice = file_get_contents('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC&tsyms=USDT&e=binance');
        $coinsprice = json_decode($coinsprice,true);

        $coinprice = file_get_contents('https://min-api.cryptocompare.com/data/histohour?fsym=BTC&tsym=USDT&limit=168&e=binance');
        $coinprice = json_decode($coinprice,true);
        $close = array_column($coinprice['Data'], 'close');
        $open = array_column($coinprice['Data'], 'open');
        $end = end($open);  

        $mainarray['binance'] = array(
                                    'price'=>$coinsprice["RAW"]['BTC']['USDT']['PRICE'],
                                    'volume'=>$coinsprice["RAW"]['BTC']['USDT']['VOLUME24HOURTO'],
                                    '24h'=>$coinsprice["RAW"]['BTC']['USDT']['CHANGEPCT24HOUR'],
                                    '1h'=>$end,
                                    'week'=>implode(',', $close)
                                );
        $coinsprice = file_get_contents('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC&tsyms=USD&e=kraken');
        $coinsprice = json_decode($coinsprice,true);

        $coinprice = file_get_contents('https://min-api.cryptocompare.com/data/histohour?fsym=BTC&tsym=USD&limit=168&e=kraken');
        $coinprice = json_decode($coinprice,true);
        $close = array_column($coinprice['Data'], 'close');
        $open = array_column($coinprice['Data'], 'open');
        $end = end($open);

        $mainarray['kraken'] = array(
                                    'price'=>$coinsprice["RAW"]['BTC']['USD']['PRICE'],
                                    'volume'=>$coinsprice["RAW"]['BTC']['USD']['VOLUME24HOURTO'],
                                    '24h'=>$coinsprice["RAW"]['BTC']['USD']['CHANGEPCT24HOUR'],
                                    '1h'=>$end,
                                    'week'=>implode(',', $close)
                                );
        $coinsprice = file_get_contents('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC&tsyms=USD&e=coinbase');
        $coinsprice = json_decode($coinsprice,true);

        $coinprice = file_get_contents('https://min-api.cryptocompare.com/data/histohour?fsym=BTC&tsym=USD&limit=168&e=coinbase');
        $coinprice = json_decode($coinprice,true);
        $close = array_column($coinprice['Data'], 'close');
        $open = array_column($coinprice['Data'], 'open');
        $end = end($open);

        $mainarray['coinbase'] = array(
                                    'price'=>$coinsprice["RAW"]['BTC']['USD']['PRICE'],
                                    'volume'=>$coinsprice["RAW"]['BTC']['USD']['VOLUME24HOURTO'],
                                    '24h'=>$coinsprice["RAW"]['BTC']['USD']['CHANGEPCT24HOUR'],
                                    '1h'=>$end,
                                    'week'=>''
                                );
        $coinsprice = file_get_contents('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC&tsyms=KRW&e=bithumb');
        $coinsprice = json_decode($coinsprice,true);

        $coinprice = file_get_contents('https://min-api.cryptocompare.com/data/histohour?fsym=BTC&tsym=KRW&limit=168&e=bithumb');
        $coinprice = json_decode($coinprice,true);
        $close = array_column($coinprice['Data'], 'close');
        $open = array_column($coinprice['Data'], 'open');
        $end = end($open);

        $mainarray['bithumb'] = array(
                                    'price'=>$coinsprice["RAW"]['BTC']['KRW']['PRICE']*0.0009,
                                    'volume'=>$coinsprice["RAW"]['BTC']['KRW']['VOLUME24HOURTO'],
                                    '24h'=>$coinsprice["RAW"]['BTC']['KRW']['CHANGEPCT24HOUR'],
                                    '1h'=>$end*0.0009,
                                    'week'=>implode(',', $close)
                                );
        if (!empty($mainarray))
        {
            $this->response([
                'data'      => $mainarray,
                'status'    => true,
                'message'   => 'Data successfully get'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'No data found'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function exchange_post()
    {   
        $config = array(
            array('field' => 'exchange', 'label' => 'Exchange', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            $this->set_response(['status' => FALSE, 'message' =>validation_errors()], REST_Controller::HTTP_OK);
        }
        else{
            $exchange = $this->post('exchange');
            $mainarray = [];
            $coinsprice = file_get_contents('https://www.livecoinwatch.com/api/coin/BTC');
            $coinsprice = json_decode($coinsprice,true);
            $binancedata = $this->filterarray($coinsprice['data'],'exchange',ucfirst($exchange));
            $mainarray[strtolower($exchange)] = [];
            foreach ($binancedata as $coin) {
                // if($coin['active'] == true) {
                    $pair = $coin['base'].'/'.$coin['quote'];
                    $is_pair = $this->common_mdl->get_table_by('pair',array('exchange_name'=>strtolower($exchange),'pair'=>$pair),'id');
                    if(!empty($is_pair)) {
                        if($coin['base'] == 'BTC') {
                            $price = (float)$coin["usd"];
                        }
                        else if($coin['quote'] == 'BTC') {
                            $price = (float)$coin["usdq"];  
                        }
                        array_push($mainarray[strtolower($exchange)], array('pair'=>$pair,'price'=>$price,'volume'=>$coin['volume']));
                    }
                // }
            }

            if (!empty($mainarray))
            {
                $this->response([
                    'data'      => $mainarray,
                    'status'    => true,
                    'message'   => 'Data successfully get'
                ], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
}
