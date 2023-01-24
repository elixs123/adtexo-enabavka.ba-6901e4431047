<?php


namespace App\Http\Controllers;

ini_set('display_errors', 1);
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

use Illuminate\Http\Request;
use App\The_OrderItem;
use App\The_Order;
use App\Product;
use App\Subject;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use GuzzleHttp\Client as GuzzleClient;
use Storage;

class WarehouseController extends Controller
{
    public $PantheonAcKey;
    public $bfRacuna;

    public function index(){
        $orders = The_Order::with('subject')
            ->where('acStatus', 'R')
        ->get();


        return view('warehouse.index', ['orders' => $orders]);
    }

    private function createPDF(){
        $pdf = \PDF::loadView('pdfview')->setPaper('a4', 'landscape');

		return $pdf->stream('pdfview.pdf');
        return \PDF::loadHTML("<h1>Test</h1>")->setPaper('a4', 'landscape')->stream();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->stream();
   

      
        
        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
        <style type="text/css">
        @font-face {
            font-family: 'Tahoma';
            font-style: normal;
            font-weight: 400px;
        }
        body {
            font-family: DejaVu Sans;
        }
        table, td, th {
            border: 1px solid;
        }
        th{
            font-align: left;
        }
        th, td{
            font-size: 7.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .naziv{
            font-weight: bold;
            padding-right: 20px;
            width: 20%;
        }
        .naziv-right{
            padding-left: 20px;
        }
        p{
            font-size: 10px;
        }
        .div1{
            width: 20%;
        }
        .div2{
            width: 20%;
        }
        .div3{
            width: 20%;
        }
        .div4{
            width: 20%;
        }
        </style>
        </head>
        HTML;
            ini_set('memory_limit', '2048M');

            $html .= '<h5 style="text-align: center;">Kalkulacija cijena za trgovinu na malo broj: 22-1004-005030 <span style="float: right;">Obrazac KCM</span></h5>';

            $html .= '<table style="border-collapse: none; border:none;width: 80%;">
            <tr style="border: none;">
                <th style="border: none;text-align: left;font-size: 10px;">Naziv i sjedište trgovca</th>
                <td style="text-align: left;border: none;font-size: 10px;">PENNY PLUS d.o.o. Sarajevo, Igmanska bb</td>
                <th style="text-align: left;border: none;font-size: 10px;">Naziv i sjedište dobavljača</th>
                <td style="text-align: left;border: none;font-size: 10px;">602009,ROYAL FOOD D.O.O, Boce 17</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;font-size: 10px;"></td>
                <td style="border: none;font-size: 10px;">71320 VOGOŠĆA</td>
                <td style="border: none;font-size: 10px;"></td>
                <td style="border: none;font-size: 10px;">71240 HADŽIĆI</td>
            </tr>
            <tr style="border: none;">
                <th style="border: none;text-align: left;font-size: 10px;padding:0;">Naziv i sjedište prodajnog</th>
                <th style="text-align: left;border: none;font-size: 10px;">MARKET VOGOŠĆA, Igmanska bb</th>
                <th style="text-align: left;border: none;font-size: 10px;">Naziv, broj i datum dokumenta na kojem</th>
                <td style="text-align: left;border: none;font-size: 10px;">Ulaz robe MARKET VOGOŠĆA</td>
            </tr>
            <tr style="border: none;">
                <th style="border: none;text-align: left;font-size: 10px;padding:0;">objekta ili drugog prodajnog</th>
                <th style="text-align: left;border: none;font-size: 10px;">71320 VOGOŠĆA</th>
                <th style="text-align: left;border: none;font-size: 10px;">je nabavka izvršena, odnosno naziv broj i</th>
                <td style="text-align: left;border: none;font-size: 10px;">22-1004-005030 / / 29.11.2022</td>
            </tr>
            <tr style="border: none;">
                <th style="border: none;text-align: left;font-size: 10px;padding:0;">mjesta (odjel, etaže i dr.)</th>
                <th style="text-align: left;border: none;font-size: 10px;"></th>
                <th style="text-align: left;border: none;font-size: 10px;">datum internog dokumenta</th>
                <td style="text-align: left;border: none;font-size: 10px;"></td>
            </tr>
            <tr style="border: none;">
                <th style="border: none;text-align: left;font-size: 10px;">Datum izrade kalkulacije</th>
                <th style="text-align: left;border: none;font-size: 10px;">29.11.2022  <span style="float: right;padding-right: 50px;padding-top: 5px;">Status kalkulacije</span></th>
                <td style="text-align: left;border:none; font-size: 15px;"><span style="border-bottom: 1px solid black;">Nepotvrđen</span></td>
                <td style="text-align: left;border: none;"></td>
            </tr>
            
            </table>';

            $html .= '<table style="padding-top: 20px;">
            <tr>
              <th style="text-align: left; padding: 5px;width: 2%;">Red. broj</th>
              <th>Šifra</th>
              <th style="width: 20%;text-align: left;padding: 5px;">Trgovački naziv robe</th>
              <th>Jed. mjere</th>
              <th>Količina</th>
              <th>Fakturna cijena po jedici mjere bez PDV-a</th>
              <th>Fakturna vrijednost bez PDV-a</th>
              <th>Zavisni troškovi bez PDV-a</th>
              <th>Nabavna cijena po jed. mjere bez PDV-a</th>
              <th>Nabavna vrijednost bez PDV-a</th>
              <th>Proc.razlike u cijeni</th>
              <th>Iznos razlike u cijeni</th>
              <th>Prodajna vrijednost bez PDV-a</th>
              <th>Prodajna cijena bez PDV-a</th>
              <th>Stopa PDV-a %</th>
              <th>Iznos PDV-a</th>
              <th>Maloprod. vrijednost sa PDV-om</th>
              <th>Maloprod. cijena sa PDV-om</th>
            </tr>
            <tr>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;">1</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;">204508</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;">MAJONEZA KESICA 47 G ROYAL</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;">KOM</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;">12,00</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">0,42</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">5,04</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">0,00</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">0,40</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">4,79</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">28,47</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">1,36</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">6,15</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">0,51</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">17,00</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">1,05</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">7,20</td>
              <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">0,60</td>
            </tr>

            <tr>
                <th colspan="3" style="text-align:left; font-size: 9px;padding: 2px;">Ukupno:</th>
                <td></td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;font-weight: bold;">12,00</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;"></td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;font-weight: bold;">5,04</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;font-weight: bold;">0,00</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;"></td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;font-weight: bold;">4,79</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;"></td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;font-weight: bold;">1,36</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;font-weight: bold;">6,15</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;"></td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;"></td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;font-weight: bold;">1,05</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;font-weight: bold;">7,20</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;"></td>
            </tr>
          </table>';

          $html .= '<table style="margin-top: 20px;border-top:none;border-left: none;border-right:none;">
          <tr style="border-top: none;">
              <th style="text-align: left;border: none;font-size: 9px;">Rekapitulacija poreza 17% </th>
              <td style="text-align: left;border: none;font-size: 9px;">Osnovica poreza: <span style="padding-left: 50px;">4,79</span></td>
              <td style="text-align: left;border: none;font-size: 9px;">Stopa PDV: 17,00% <span style="padding-left: 50px;">0,81</span></td>
              <td style="text-align: left;border: none;font-size: 9px;">Ukupna vrijednost fakture: <span style="padding-left: 50px;">5,60</span></td>
          </tr></table>';

          $html .= '<table style="width: 100%;border: none;">
          <tr style="border: none;">
            <th style="border: none;"></th>
            <th style="border: none;"></th>
            <th style="border: none;"></th>
            <th style="border: none;"></th>
            <th style="border: none;font-size: 10px;text-align:right;padding-right: 5px; width: 15%;">Odgovorno lice</th>
          </tr>
          <tr style="border: none;margin-top: 20px;">
            <th style="border: none;"></th>
            <th style="border: none;"></th>
            <th style="border: none;"></th>
            <th style="border: none;"></th>
            <th style="border-bottom: 1px solid black;border-top: none;border-left:none;border-right:none;font-size: 10px;text-align:right;padding-right: 5px;padding-top: 30px;"></th>
          </tr>
          </table>';
        
          $html .= '<h6 style="padding: 5px;margin: 0;">Lista povezanih dokumenata:</h6>';
          $html .= '<h6 style="margin:0;padding:0;position:absolute;left: 55%;top:56%;">M.P</h6>';
          $html .= '<table style="width: 50%;border: none;">
          <tr>
            <th>Broj</th>
            <th>Faktura dobavljača</th>
            <th>Datum</th>
            <th>Naziv</th>
            <th>Iznos</th>
          </tr>
          <tr>
            <th style="">1</th>
            <th style="">1</th>
            <th style="">1</th>
            <th style="">1</th>
            <th style="">1</th>
          </tr>
          </table>';


            $html .= '</body></html>';

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML('<h1>Test</h1>');
            $pdf->render();
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();

        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream(); $pdf->loadHTML($html)->setPaper('a4', 'landscape');
            

      // return $pdf->stream());
       //return $pdf->download('invoice.pdf');

        $content = $pdf->download()->getOriginalContent();
        return $content;
        //Storage::put('public/csv/name.pdf',$content) ;
    }

    public function order($id){
        $order = The_Order::where('id', $id)->firstOrFail();
        $pantheonOrder = true;

        $orderItems = The_OrderItem::where('orderNumber', $order->orderNumber)->with('items')
        ->orderBy('anNo', 'desc')->get();

        $subject = Subject::where('acSubject', $order->acSubject)->first();

        $acPayer = Subject::where('acSubject', $order->acPayer)->first();

        return view('warehouse.document', ['pantheonOrder' => $pantheonOrder, 'order' => $order, 'orderItems' => $orderItems, 'subject' => $subject, 'acPayer' => $acPayer]);
    }

    public function orderSave($id, Request $request){
        $this->createPDF();

        /*
        if($request->has('updateAnQty')){
            The_OrderItem::where('orderNumber', $request->input('orderNumber'))
                            ->where('anNo', $request->input('anNo'))
                            ->update([
                                'anQty' => $request->input('anQty')
                            ]);

            $sumAnForPay = The_OrderItem::where('orderNumber', $request->input('orderNumber'))->sum(DB::raw('anForPay * anQty'));


            The_Order::where('orderNumber', $request->input('orderNumber'))->update([
                'anForPay' => $sumAnForPay
            ]);

            return redirect()->back();
        }


        if($request->has('acStatus')){
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer 4|J8CTdUKEGzNf9oSZkbcknSmUxuggrqY7D2bXmWxg',
            ];
    
            $client = new GuzzleClient([
                'headers' => $headers,
                'verify' => false
            ]);
    
            try {
                $r = $client->request('POST', '65.109.108.106/api/insertordernew', ['form_params' => [
                    'brojnarudzbe' =>  '#4223f',
                    'komercijalista' => 1,
                    'kupac' => [
                        'kupacemailadresa' => 'test@gmail.com', 
                        'kupactelefon' => '',
                        'kupacadresa' => 'Ćehaje',
                        'kupacptt' => '75350',
                        'kupacgrad' => 'SREBRENIK',
                        'kupacid' => '0001ALI',
                        'tipkupca' => 'business_client',
                        'koddrzave' => 'BA'
                    ],
                    'primalac' => [
                        'primalacnaziv' => 'ALI COMPANY d.o.o.', 
                        'primalactelefon' => 'fskfs',
                        'primalacadresa' => 'Ćehaje',
                        'primalacptt' => '75350',
                        'primalacgrad' => 'SREBRENIK',
                        'primalacemailadresa' => 'test@gmail.com',
                        
                        'primalacid' => '0001ALI'
                    ],
                    'artikli' => json_encode([
                        [
                            'poz' => '1', 
                            'artikalid' => '004168',
                            'kol' => 20.00,
                            'diskont1' => 20.00,
                            'diskont2' => 20.00,
                            'diskont3' => 20.00
                        ],[
                            'poz' => '2', 
                            'artikalid' => '004168',
                            'kol' => 20.00,
                            'diskont1' => 20.00,
                            'diskont2' => 20.00,
                            'diskont3' => 20.00
                        ]
                    ])
                ]
            ],['verify' => false]);
    
                $response = $r->getBody(true)->getContents();
                
               
                $responseData = json_decode($r->getBody(), true);

                  $this->PantheonAcKey =  $responseData["podaci"]["HeadInvoice"][0]["BrojFakture"];
                  $this->printBill();
                
    
            }  catch (ClientException $e) {
                
                echo $e->getMessage()['message'];
                die;
               
            }

            $request->validate([
                'orderNumber' => 'required:string:max:100'
            ]);

            The_Order::where('id', $id)
                            ->update([
                                'acStatus' => 'O'
                            ]);
                            
            return redirect()->route('warehouse.index');
        }

        */
    }

    private function printBill(){
        $headers = [
            'Content-Type' => 'text/xml',
        ];

        $client = new GuzzleClient([
            'headers' => $headers,
            'verify' => false
        ]);

        try {
            $r = $client->request('POST', 'http://10.20.99.179:8085/stampatifiskalniracun', ['body' =>
                "<?xml version='1.0' encoding='utf-8'?><RacunZahtjev><BrojZahtjeva>1164220</BrojZahtjeva><VrstaZahtjeva>0</VrstaZahtjeva><NoviObjekat><Datum>2022-11-16T07:50:18</Datum><Napomena>2232520110180
                POS KUPAC
                Sarajevo BIH 
                Dobro došli kod NAS       
                </Napomena>
                <StavkeRacuna>
                
                <RacunStavka>
                <artikal><Sifra>139797</Sifra>
                    <Naziv>STAVKE</Naziv>
                    <JM/><Cijena>1.00</Cijena>
                    <Stopa>E</Stopa>
                </artikal>
                    <Kolicina>1.000</Kolicina>
                    <Rabat>0.00</Rabat>
                </RacunStavka>
                
                </StavkeRacuna>
                <VrstePlacanja>
                <VrstaPlacanja>
                <Oznaka>Gotovina</Oznaka>
                <Iznos>1.00</Iznos>
                </VrstaPlacanja><VrstaPlacanja>
                <Oznaka>Gotovina</Oznaka>
                <Iznos>0.00</Iznos>
                </VrstaPlacanja></VrstePlacanja>
                </NoviObjekat>
                </RacunZahtjev>"
        ],['verify' => false]);

            $response = $r->getBody(true)->getContents();
            $responseData = json_decode($r->getBody(), true);
            $doc = new \DOMDocument;
            $doc->loadXML($response);

            
            
            $this->bfRacuna = str_replace("BrojFiskalnogRacuna", "",$doc->getElementsByTagName('Odgovor')->item(0)->nodeValue); 
            
            $this->updateTheMove();

        }  catch (ClientException $e) {
            dd($e);
            echo $e->getMessage();
            
        }
    }

    private function updateTheMove(){
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 4|J8CTdUKEGzNf9oSZkbcknSmUxuggrqY7D2bXmWxg',
        ];

        $client = new GuzzleClient([
            'headers' => $headers,
            'verify' => false
        ]);

        try {
            $r = $client->request('POST', '65.109.108.106/api/updateorder', ['form_params' => [
                'bf' =>  $this->bfRacuna,
                'brojfakture' => $this->PantheonAcKey
            ]
        ],['verify' => false]);

            $response = $r->getBody(true)->getContents();
            
           dd($response);
            $responseData = json_decode($r->getBody(), true);

            
            

        }  catch (ClientException $e) {
            
            echo $e->getMessage()['message'];
            die;
           
        }
    }
}
