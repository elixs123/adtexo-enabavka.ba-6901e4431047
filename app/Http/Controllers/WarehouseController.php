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
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Storage;

class WarehouseController extends Controller
{
    public $PantheonAcKey;
    public $bfRacuna;
    public $orderNumber;

    public function index(){
        $orders = The_Order::with('subject')
            ->where('acStatus', 'R')
        ->get();

        $pantheonOrder = false;


        return view('warehouse.index', ['orders' => $orders, 'pantheonOrder' => $pantheonOrder]);
    }

    private function createPDF(){
        ini_set('memory_limit', '2048M');

        $order = The_Order::where('orderNumber', $this->orderNumber)->firstOrFail();

        $orderItems = The_OrderItem::with('items')->where('orderNumber', $this->orderNumber)->get();

        $acSubject = Subject::where('acSubject', $order->acPayer)->firstOrFail();
        
        $qrcode = base64_encode(\QrCode::format('svg')->size(100)->errorCorrection('H')->generate($this->PantheonAcKey));
        
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
            font-weight: lighter;
        }
        .border-table {
            border-top: 1px solid;
            border-bottom: 1px solid;
        }
        .border-table th{
            border-bottom: 1px solid black;
            background-color: #ddd;
        }
        .pfonts{
            border-radius: 20px;
        }
        .pfonts th{
            font-size: 10px;
        }
        .pfonts h3{
            margin: -4px;
            padding: 0px;
        }
        .pfonts1 h2{
            margin: -4px;
            padding: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th{
            font-align: left;
        }
        th, td{
            font-size: 7.5px;
        }
        h3{
            padding:5px;
            margin:0px;
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
            font-size: 8px;
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
            
        

            $html .= '<img style="width: 270px;" src="data:image/jpeg;base64,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" >';


            $html .= '<img style="margin-left: 70px;" src="data:image/png;base64,'.$qrcode.'">';
            $html .= '<h4>Poreska faktura/Otpremnica</h4>';
            $html .= '<h4>'.$this->PantheonAcKey.'</h4>';

            $html .= '<table style="padding-top: 20px; width: 25%;">
            <tr>
              <th style="text-align: left;font-weight: bold;">Dat.izdavanja:</th>
              <th style="text-align: left;font-weight: normal;">'.Carbon::parse($order->adDate)->format('d.m.Y').'</th>
            </tr>
            <tr>
                <th style="text-align: left;">Datum valute</th>
                <th style="text-align: left;font-weight: normal;">'.Carbon::parse($order->anDaysForValid)->format('d.m.Y').'</th>
            </tr>
            <tr>
                <th style="text-align: left;">Mjesto, dana</th>
                <th style="text-align: left;font-weight: normal;">Sarajevo, '.Carbon::parse($order->adDate)->format('d.m.Y').'</th>
            </tr>
            <tr>
                <th style="text-align: left;">Način plačanja</th>
                <th style="text-align: left;font-weight: normal;">Virmansko</th>
            </tr>

          </table>';

          $html .= '<table style="width: 40%; position: absolute;top:0px;right:0px;" class="pfonts1">
          <tr >
            <th style="text-align: right;padding:0;margin:0;"><h2>ADTEXO DOO SARAJEVO</h2></th>
          </tr>
          <tr>
              <th style="text-align: right;padding:0;margin:0;"><h2>Marka Marulića 2</h2></th>
          </tr>
          <tr>
              <th style="text-align: right;padding:0;margin:0;"><h2>71000 Sarajevo</h2></th>
          </tr>
        </table>';

          $html .= '<table style="width: 40%; position: absolute;top:130px;right:0px;border: 1px solid black;
          
          background-color: #fff;
          -webkit-border-radius:4px;
          border-radius: 10px;
          border-collapse: separate;" class="pfonts">
          <tr >
            <th style="text-align: left;font-weight: bold;padding-left: 20px;padding-top:20px;"><h3>'.$acSubject->acName2.'</h3></th>
          </tr>
          <tr>
              <th style="text-align: left;padding-left: 20px;padding-top:5px;"><h3>'.$acSubject->acAddress.'</h3></th>
          </tr>
          <tr>
              <th style="text-align: left;padding-left: 20px;padding-top:5px;"><h3>'.$acSubject->acPost.' '.$acSubject->acFieldSH.'</h3></th>
          </tr>
          <tr>
              <th style="text-align: left;padding-left: 20px;padding-top:25px;"><h3>ID: '.$acSubject->acCode.'</h3></th>
          </tr>
          <tr>
              <th style="text-align: left;padding-left: 20px;padding-top:5px;padding-bottom:20px;"><h3>PDV: </h3></th>
          </tr>

        </table>';

        $html .= '<h3 style="position: absolute;top:275px;right:200px">BF: '.$this->bfRacuna.'</h3';

            $html .= '<table style="padding-top: 20px;" class="border-table">
            <tr>
              <th style="text-align: left; padding: 5px;width: 2%;">Poz</th>
              <th style="text-align: left;padding: 5px;width: 15%;">Šifra artikla</th>
              <th style="width: 20%;text-align: left;padding: 5px;">Naziv</th>
              <th>JM</th>
              <th>Količina</th>
              <th>Cijena</th>
              <th>Cijena bez PDV-a</th>
              <th>R1(%)</th>
              <th>R2(%)</th>
              <th>R3(%)</th>
              <th>Vrijednost</th>
              <th>Ukupno sa PDV-om</th>
            </tr>';

            foreach($orderItems as $item){
                $html .= '
                <tr>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;">'.$item->anNo.'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;">'.$item->acIdent.'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;">'.$item->items->acName.'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;text-align:right;">KOM</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;text-align:right;">'.$item->anQty.'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">'.number_format($item->anPrice * 1.17, 2, '.', ',').'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">'.number_format($item->anPrice, 2, '.', ',').'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">'.$item->anRebate1.'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">'.$item->anRebate2.'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">'.$item->anRebate3.'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">'.number_format($item->anForPay * $item->anQty, 2,'.', ',').'</td>
                <td style="padding-left: 10px;padding-bottom: 2px;padding-top:2px;padding-right: 4px; text-align: right;">'.number_format(($item->anForPay * $item->anQty) * 1.17, 2, '.', ',').'</td>
                </tr>';
            }

            $html .= '</table>';


          $html .= '<table style="width: 100%;border: none;">
          <tr style="border: none;">
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 5%;"></th>
            <th style="border: none;width: 35%;text-align:left;"">Prodajna vrijednost bez PDV-a </th>
            <th style="border: none;font-size: 10px;text-align:right;width: 10%;">'.number_format($order->anForPay, 2, '.', ',').'</th>
          </tr>
          <tr style="border: none;">
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 20%;"></th>
            <th style="border: none;text-align:left;">+ PDV %</th>
            <th style="border: none;font-size: 10px;text-align:right; width: 10%;">'.number_format(($order->anForPay * 1.17) - ($order->anForPay * 1.17) / 1.17 , 2, '.', ',').'</th>
          </tr>

          <tr style="border: none;">
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 20%;"></th>
            <th style="border: none;text-align:left;">Brza pošta</th>
            ';
            $epost = 7.00;
           switch($order->anForPay){
            case($order->anForPay < 100):
                $ePost = 7.00;
                $html .= '<th style="border: none;font-size: 10px;text-align:right; width: 10%;">'.number_format($ePost, 2, '.', ',').'</th>';
                break;
            case($order->anForPay >= 100 && $order->anForPay < 160):
                $ePost = 3.50;
                $html .= '<th style="border: none;font-size: 10px;text-align:right; width: 10%;">'.number_format($ePost, 2, '.', ',').'</th>';
                break;
            case($order->anForPay >= 160):
                    $ePost = 0.00;
                    $html .= '<th style="border: none;font-size: 10px;text-align:right; width: 10%;">'.number_format($ePost, 2, '.', ',').'</th>';
                    break;
           }
         $html .= '</tr>
          
            <tr>
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 20%;"></th>
            <th colspan="2" style="border: none;font-size: 10px;text-align:left; width: 10%;"><hr></th>
            </tr>
            <tr>
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 15%;"></th>
            <th style="border: none;width: 20%;"></th>
            <th style="border: none;text-align:left;">Fakturna vrijednost sa PDV-om</th>
            <th style="border: none;font-size: 10px;text-align:right;width: 10%;">'.number_format(($order->anForPay * 1.17) + $ePost, 2, '.', ',').'</th>
            </tr>
          </table>';
        
          $html .= '<h5 style="color: red;margin-top: 150px;">Vaša dugovanja na dan 24.01.2023. iznose:</h5>';
          $html .= '<h5 style="color: red;padding:0;margin:0;">Dospjelo:</h5>';
          $html .= '<h5 style="color: red;padding:0;margin:0;">Nedospijelo:</h5>';

          $html .= '<table style="padding-top: 20px;" class="border-table">
            <tr>
              <th style="text-align: left; padding: 5px;width: 30%;">Rekapitulacija poreza</th>
              <th style="text-align: right;padding: 5px;">Osnovica poreza</th>
              <th style="text-align: right;">Vrijednost PDV-a</th>
              <th style="text-align: right;">Vrijednost poreske fakture</th>
            </tr>

            <tr>
              <td style="text-align: left;padding-right: 10px;padding-bottom: 2px;padding-top:2px;">PDV - isporuke/prijemi</td>
              <td style="text-align: right;padding-right: 10px;padding-bottom: 2px;padding-top:2px;">'.number_format($order->anForPay, 2, '.', ',').'</td>
              <td style="text-align: right;padding-right: 10px;padding-bottom: 2px;padding-top:2px;">'.number_format(($order->anForPay * 1.17) - ($order->anForPay * 1.17) / 1.17 , 2, '.', ',').'</td>
              <td style="text-align: right;padding-right: 10px;padding-bottom: 2px;padding-top:2px;">'.number_format(($order->anForPay * 1.17) + $ePost, 2, '.', ',').'</td>
            </tr>
          </table>';

          $html .= '<p>Povrat robe mogu ostvariti kupci sa kojima imamo potpisan ugovor o poslovnoj saradnji.</p>';
          $html .= '<p>Prodajni predstavnici AdTexo kompanije nisu ovlašteni preuzeti povrat robe od kupca.</p>';
          $html .= '<p style="width: 55%;">Reklamacije u roku od 3 dan od datuma prijema računa na: ...;
          U slučaju neblagovremenog plaćanja, zaračunavamo zakonsku zateznu kamatu.
          U skladu sa članom 9. Zakona o računovodstvu, račun sadrži identifikacionu oznaku odgovornog lica za izdavanje dokumenta i kao takva važi bez pečata i potpisa.</p>';
        
          $html .= '<table style="padding-top: 20px;">
          <tr>
            <th style="text-align: center; padding: 5px;margin: 25px;">Odgovorna osoba</th>
            <th style="text-align: center;padding-right: 15px;">Fakturisao</th>
            <th style="text-align: center;padding-right: 15px;">Kontrolisao:</th>
            <th style="text-align: center;padding-right: 15px;">Robu izdao</th>
            <th style="text-align: center;padding-right: 15px;">Robu/Fakturu primio</th>
          </tr>

          <tr>
            <td style="text-align: center;padding-right: 10px;padding-bottom: 2px;padding-top:2px;">
            Adi Kurtović
            <hr>
            </td>
            <td style="text-align: center;padding-right: 10px;padding-bottom: 2px;padding-top:13px;">
             <hr>
             </td>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 2px;padding-top:13px;"><hr></td>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 2px;padding-top:13px;"><hr></td>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 2px;padding-top:13px;"><hr></td>
          </tr>
        </table>';

            $html .= '</body></html>';

            $dompdf = new Dompdf(array('enable_remote' => true));
            $dompdf->loadHtml($html);
            
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');
            
            // Render the HTML as PDF
            $dompdf->render();
            
            // Output the generated PDF to Browser
            $dompdf->stream("", array("Attachment" => false));
            
            return false;
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
        $orderNumber = $request->input('orderNumber');

        $this->orderNumber = $orderNumber;


        $itemsToSend = [];

        $order = The_Order::where('orderNumber', $orderNumber)->firstOrFail();

        $orderItems = The_OrderItem::where('orderNumber', $orderNumber)->get();


        foreach($orderItems as $item){
            $itemsToSend[] = [
                'poz' => $item->anNo, 
                'artikalid' => $item->acIdent,
                'kol' => $item->anQty,
                'diskont1' => $item->anRebate1,
                'diskont2' => $item->anRebate2,
                'diskont3' => $item->anRebate3
            ];
        }
        


        $acSubject = Subject::where('acSubject', $order->acSubject)->firstOrFail();

        $acPayer = Subject::where('acSubject', $order->acPayer)->firstOrFail();

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
                    'brojnarudzbe' =>  $order->orderNumber,
                    'komercijalista' => 1,
                    'kupac' => [
                        'kupacemailadresa' => 'nista@gmail.com', 
                        'kupactelefon' => $acSubject->acPhone,
                        'kupacadresa' => $acSubject->acAddress,
                        'kupacptt' => $acSubject->acPost,
                        'kupacgrad' => $acSubject->acFieldSH,
                        'kupacid' => $acSubject->acSubject,
                        'kupacnaziv' => $acSubject->acName2,
                        'tipkupca' => 'business_client',
                        'koddrzave' => 'BA'
                    ],
                    'primalac' => [
                        'primalacnaziv' => $acPayer->acName2, 
                        'primalactelefon' => $acPayer->acPhone,
                        'primalacadresa' => $acPayer->acAddress,
                        'primalacptt' => $acPayer->acPost,
                        'primalacgrad' => $acPayer->acFieldSH,
                        'primalacemailadresa' => 'nista@gmail.com',
                        
                        'primalacid' => $acPayer->acSubject
                    ],
                    'artikli' => json_encode(
                        $itemsToSend
                    )
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
            
            $responseData = json_decode($r->getBody(), true);

            $this->createPDF($this->orderNumber);
            
            

        }  catch (ClientException $e) {
            
            echo $e->getMessage()['message'];
            die;
           
        }
    }
}
