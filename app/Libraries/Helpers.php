<?php

function is_json($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}

function strip_domain($url)
{
	return str_replace(url('/'), '', $url);
}

/**
 * Oduzima dva datuma po timestamp-u.
 * @param date $start_date
 * @param date $end_date
 * @return int
 */
 
function subtract_dates_in_minutes($start_date, $end_date)
{
	return round(abs(strtotime($start_date) - strtotime($end_date)) / 60, 0);
}

function subtract_dates($start_date, $end_date)
{
    return round(((strtotime($start_date) - strtotime($end_date)) / 86400));
}

/**
* Dobavlja IP adresu posjetioca
* @return string
*/
function get_real_ip_addr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }
    return htmlspecialchars($ip);
}

function r_collect($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = r_collect($value);
                $array[$key] = $value;
            }
        }

        return collect($array);
    }


function genDateTime($date, $start)
{
    return request()->get($date) . ' ' . request()->get($start) . ':00';
}

/**
 * Zamjena za file_get_contents() embeded php funkciju.
 * @param string $url
 * @return string
 */
function file_get_contents_curl($url)
{
    $ch = curl_init();
    $timeout = 5;

    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

/**
 * Repeate string given number of times.
 * @param int $how_many
 * @param string $what
 * @return string
 */
function repeater($how_many = 0, $what = ' - ')
{
    $out = '';

    for($i = 0; $i < $how_many; $i++)
    {
        $out.= $what;
    }

    return $out;
}

/**
* Generiše collection za HTML::select
* @return object
*/
function dropdown($items, $label = 'name', $value = 'id', $newLabelValue = 'Odaberite', $newOptionValue = '')
{
    $items->prepend([$label => $newLabelValue, $value => $newOptionValue]);

    return $items->pluck($label, $value);
}

/**
* Generiše collection za HTML::select
* @return object
*/
function dropdown_array($items, $label = 'name', $value = 'id', $newLabelValue = 'Odaberite') {
    $results[''] = $newLabelValue;

    foreach ($items as $id => $item) {
        $results[$item[$value]] = $item[$label];
    }

    return $results;
}

/**
* Dodaje element na početak niza. Korisno kod dodavanje placeholder vrijednosti za select opcije.
* @param array $array
* @param string $key
* @param string $value
* @return array
*/
function addChooseItem($array, $key = '', $value = 'Odaberite')
{
    return array($key => $value) + $array;
}

/**
* Provjera da li je zadani string validan URL
* @param string $url
* @return boolean
*/
function isUrl($url)
{
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE)
    {
        return false;
    }

    return true;
}

/**
* Izdvaja vrijednosti iz kolekcije podataka u array na osnovu ključa/kolone.
* @param collection $values
* @param string $col
* @return array
*/
function extractColumn($values, $col)
{
    $return = [];
    
    foreach($values as $value)
    {
        $return[] = $value->$col;
    }
    
    return $return;
}


/**
 * Check is logged user superadmin.
 *
 * @return boolean
 */
function isSuperAdmin()
{
    $superIds = [1];

    if(in_array(auth()->id(), $superIds))
    {
        return true;
    }

    return false;
}

function setQueryString($url, $key, $val) {
    $pUrl = parse_url($url);
    if (isset($pUrl['query']))
        parse_str($pUrl['query'], $pUrl['query']);
    else
        $pUrl['query'] = [];
    $pUrl['query'][$key] = $val;

    $scheme = isset($pUrl['scheme']) ? $pUrl['scheme'] . '://' : '';
    $host = isset($pUrl['host']) ? $pUrl['host'] : '';
    $path = isset($pUrl['path']) ? $pUrl['path'] : '';
    $path = count($pUrl['query']) > 0 ? $path . '?' : $path;

    return $scheme . $host . $path . http_build_query($pUrl['query']);
}

/**
* Dobavlja željenu vrijednost iz stringa razdovjenog delimiterima.
 *
* @param string $string
* @param int    $element
* @param string $delmiter
* @return string
*/
function get_string_part($string, $element, $delmiter = ' - ')
{
    $array = explode($delmiter, $string);
    
    return isset($array[$element]) ? $array[$element] : null;
}
function check_is_valid_date($myDateString){
    return (strtotime($myDateString))?true:false;
}

function remove_bom($data) {
    if (0 === strpos(bin2hex($data), 'efbbbf')) {
       return substr($data, 3);
    }
    return $data;
}

/**
 * Get code book options.
 *
 * @param string $type
 * @return \Illuminate\Database\Eloquent\Collection
 */
function get_codebook_opts($type = 'status')
{
    return \Illuminate\Support\Facades\Cache::rememberForever('code-book.'.$type, function() use($type) {
        $codeBook = new App\CodeBook();
		$codeBook->limit = null;
        $codeBook->typeGroup = $type;
    
        return $codeBook->getAll();
    });
}

function format_bytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }

/**
 * Determine if all of the given abilities should be granted for the current user.
 *
 * @param  iterable|string  $abilities
 * @param  array|mixed  $arguments
 * @return bool
 */
function can($abilities, $arguments = []) {
    return app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($abilities, $arguments);
}

/**
 * Get domain name.
 *
 * @return string
 */
function get_domain_name() {
    return config('app.name');
}

/**
 * Get class name.
 *
 * @param object $class_name
 * @return bool|int|string
 */
function get_class_name($class_name) {
    // Class
    if (is_object($class_name)) {
        $class_name = get_class($class_name);
    }
    
    // Check
    if ($pos = strrpos($class_name, '\\')) {
        return substr($class_name, $pos + 1);
    }
    
    // Return
    return $class_name;
}

/**
 * Get notification icon.
 *
 * @param string $type
 * @return string
 */
function get_notification_icon($type) {
    switch ($type) {
        case 'task':
            return 'fa-tasks';
            break;
        case 'hearing':
            return 'fa-calendar';
            break;
        default:
            return '';
            break;
    }
}

/**
 * Check is device mobile device
 * @return boolean
 */
function isMobile()
{
    $mobile = new \App\Libraries\MobileDetect();
    
    return $mobile->isMobile();
}

/**
 * Check is device tablet device
 * @return boolean
 */
function isTablet()
{
    $mobile = new \App\Libraries\MobileDetect();
    
    return $mobile->isTablet();
}

/**
 * Na osnovu prednog datuma, vraća dan u sedmici u obliku 'Ponedeljak, Utorak ... '
 * @param date $date
 * @return string
 */
function dayInWeek($date, $version = 'short')
{
    if ($version == 'full')
    {
        $days = array(
            1 => 'Ponedeljak',
            2 => 'Utorak',
            3 => 'Srijeda',
            4 => 'Četvrtak',
            5 => 'Petak',
            6 => 'Subota',
            7 => 'Nedelja'
            );
    }
    else
    {
        $days = array(
            1 => 'Pon',
            2 => 'Uto',
            3 => 'Sri',
            4 => 'Čet',
            5 => 'Pet',
            6 => 'Sub',
            7 => 'Ned'
            );
    }
    
    return $days[date('N', strtotime($date))];
}

/**
 * Return formmated price for display
 *
 * @param float $price Product price
 * @param int $decimals
 * @return string Formatted price
 */
function format_price($price, $decimals = 2)
{
    return number_format(floatval(str_replace(',', '.', $price)), $decimals, ',', '.');
}

/**
 * String 2 Float
 * @param string $input
 * @return float
 */
function convert2float($input) {
    return floatval(str_replace(['.', ','], ['', '.'], $input));
}
/**
 * Lang to icon.
 *
 * @param string $lang_id
 * @return string
 */
function langToIcon($lang_id) {
    switch($lang_id) {
        case 'bs': $icon = 'ba'; break;
        case 'sr': $icon = 'rs'; break;
        case 'en': $icon = 'gb'; break;
        default : $icon = 'ba'; break;
    }
    
    return $icon;
}

/**
 * @param int $weekNumber
 * @return \Carbon\Carbon
 */
function getDateFromWeekNumber($weekNumber) {
    $difference = $weekNumber - now()->weekOfYear;
    
    return \Carbon\Carbon::createFromTimestamp(strtotime("{$difference} week"));
}

/**
 * @param string $date
 * @param string $format
 * @return \Carbon\Carbon
 */
function toCarbonDate($date, $format = 'Y-m-d H:i:s') {
    return \Carbon\Carbon::createFromFormat($format, $date);
    
}

/**
 * @return array
 */
function getDocumentStatusSorted() {
    $cds = get_codebook_opts('document_status')->keyBy('code');
    
    return [
        'draft' => $cds['draft'],
        'in_process' => $cds['in_process'],
        'in_warehouse' => $cds['in_warehouse'],
        'for_invoicing' => $cds['for_invoicing'],
        'invoiced' => $cds['invoiced'],
        'canceled' => $cds['canceled'],
        'returned' => $cds['returned'],
        'reversed' => $cds['reversed'],
    ];
}

/**
 * @param $points
 * @param $countryId
 * @return float|int
 */
function getLoyaltyValue($points, $countryId) {
    if ($points <= 0) {
        return 0;
    } else if ($points < 1000) {
        return $points * (($countryId == 'bih') ? 0.5 : 25);
    } else if ($points < 5000) {
        return $points * (($countryId == 'bih') ? 1 : 50);
    } else if ($points < 10000) {
        return $points * (($countryId == 'bih') ? 1.5 : 75);
    } else {
        return $points * (($countryId == 'bih') ? 2 : 100);
    }
}

/**
 * @param array $query
 * @param array $parameters
 * @param bool $build
 * @param array $only
 * @param array $exclude
 * @return array|string
 */
function httpQuery(array $query, $parameters = [], $build = false, $only = [], $exclude = []) {
    foreach ($parameters as $key => $value) {
        $query[$key] = $value;
    }
    
    foreach ($query as $key => $value) {
        if (count($only) && !in_array($key, $only)) {
            unset($query[$key]);
        }
        
        if (count($exclude) && in_array($key, $exclude)) {
            unset($query[$key]);
        }
    }
    
    return $build ? http_build_query($query) : $query;
}

/**
 * @param string $deliveryType
 * @param string $countryId
 * @param float $neto
 * @param float $deliveryCost
 * @return float|int
 */
function calcDeliveryCost(string $deliveryType, string $countryId, float $neto, float $deliveryCost = 0) {
    if ($deliveryCost > 0) {
        return $deliveryCost;
    }
    
    if ($deliveryType == 'paid_delivery') {
        if ($countryId == 'bih') {
            if ($neto < 100) {
                return config('app.delivery_cost.bih.full');
            } else if ($neto < 160) {
                return config('app.delivery_cost.bih.half');
            }
        }
        
        if ($countryId == 'srb') {
            if ($neto < 6500) {
                return config('app.delivery_cost.srb.full');
            } else if ($neto < 9000) {
                return config('app.delivery_cost.srb.half');
            }
        }
    }
    
    return 0;
}

/**
 * @param float $deliveryCost
 * @param string $clientType
 * @param int $taxRate
 * @return float|int
 */
function clientTypeDeliveryCost($deliveryCost, $clientType, $taxRate) {
    if ($clientType == 'business_client') {
        $deliveryCost = getPriceWithVat($deliveryCost, $taxRate);
    }
    
    return $deliveryCost;
}

/**
 * @param float $price
 * @param float|int $d1
 * @param float|int $d2
 * @param float|int $d3
 * @param int $precision
 * @return float
 */
function calculateDiscount($price, $d1, $d2 = 0, $d3 = 0, $precision = 3) {
    $discountedPrice = $price;
    
    if($d1 > 0)
    {
        $discountedPrice = $discountedPrice - round(($price * $d1 / 100), $precision);
    }
    
    if($d2 > 0)
    {
        $discountedPrice = $discountedPrice - round(($discountedPrice * $d2 / 100), $precision);
    }
    
    if($d3 > 0)
    {
        $discountedPrice = $discountedPrice - round(($discountedPrice * $d3 / 100), $precision);
    }
    
    return round($discountedPrice, $precision);
}

/**
 * @param float|int $price
 * @param int $taxRate
 * @param int $precision
 * @return float|int
 */
function getPriceWithoutVat($price, $taxRate, $precision = 3) {
    if ($taxRate <= 0) {
        return $price;
    }
    
    $taxRate = ($taxRate + 100) / 100;
    
    return round($price / $taxRate, $precision);
}

/**
 * @param float|int $price
 * @param int $taxRate
 * @param int $precision
 * @return float|int
 */
function getPriceWithVat($price, $taxRate, $precision = 3) {
    if ($taxRate <= 0) {
        return $price;
    }
    
    $taxRate = 1 + ($taxRate / 100);
    
    return round($price * $taxRate, $precision);
}

/**
 * @param float|int $price
 * @param int $taxRate
 * @param int $precision
 * @return float|int
 */
function getVatFromPrice($price, $taxRate, $precision = 3) {
    if ($taxRate <= 0) {
        return 0;
    }
    
    return round($price * ($taxRate / 100), $precision);
}

/**
 * @param float|int $discount
 * @param float|int $value
 * @param bool $formatted
 * @return float|int|string
 */
function getDiscountedValue($discount, $value, $formatted = false, $precision = 3) {
    $value = ($discount / 100) * $value;
    
    if ($formatted) {
        return format_price($value, $precision);
    }
    
    return round($value, $precision);
}

/**
 * @return \App\Support\Scoped\ScopedAction
 */
function scopedAction() {
    return app('ScopedAction');
}

/**
 * @return \App\Support\Scoped\ScopedStock
 */
function scopedStock() {
    return app('ScopedStock');
}

/**
 * @param float $discounted
 * @param float $original
 * @param int $precision
 * @return float
 */
function calculateDiscountPercent($discounted, $original, $precision = 2) {
    if ($original <= 0) {
        return 0;
    }
    
    $percent = (1 - ($discounted / $original)) * 100;
    
    return round($percent, $precision);
}

/**
 * Get asset version.
 *
 * @return string
 */
function assetVersion()
{
    if (config('app.env') == 'local')
    {
        // return '?v=202105241120';
        return '?v=' . time();
    }
    
    return '?v=202109211425';
}

/**
 * @param mixed $value
 * @return bool
 */
function not_null($value) {
    return ! is_null($value);
}


/**
 * @param int|float $percent
 * @param int|float $value
 * @param false $formatted
 * @return float|string
 */
function calculatePercentValue($percent, $value, $formatted = false) {
    if ($value == 0) {
        return $value;
    }
    
    $value = round(($percent / 100) * $value, 2);
    
    if ($formatted) {
        return format_price($value, 2);
    }
    
    return $value;
}
