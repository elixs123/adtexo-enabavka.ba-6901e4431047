<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class CodeBooksTableSeeder
 */
class CodeBooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('code_books')->truncate();
    
        $code_books = [
            ['id' => '1','name' => 'Aktivan','code' => 'active','type' => 'status','background_color' => '#28a745','color' => '#ffffff','created_at' => null,'updated_at' => '2020-02-12 10:38:32'],
            ['id' => '2','name' => 'Neaktivan','code' => 'inactive','type' => 'status','background_color' => '#adb5bd','color' => '#ffffff','created_at' => null,'updated_at' => '2020-02-12 10:38:39'],
            ['id' => '3','name' => 'Odgovorna osoba za narudžbe','code' => 'responsible_person','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2019-12-12 14:10:36','updated_at' => '2020-02-11 13:36:54'],
            ['id' => '5','name' => 'Kontakt u računovodstvu','code' => 'payment_person','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2019-12-12 14:13:15','updated_at' => '2020-12-22 15:47:04'],
            ['id' => '6','name' => 'Komercijalista','code' => 'salesman_person','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2019-12-12 14:14:00','updated_at' => '2019-12-12 14:14:00'],
            ['id' => '7','name' => 'Supervizor','code' => 'supervisor_person','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2019-12-12 14:15:03','updated_at' => '2019-12-12 14:15:03'],
            ['id' => '10','name' => 'Pravno lice','code' => 'business_client','type' => 'client_types','background_color' => null,'color' => null,'created_at' => '2019-12-13 11:53:40','updated_at' => '2019-12-13 11:53:40'],
            ['id' => '11','name' => 'Fizičko lice','code' => 'private_client','type' => 'client_types','background_color' => null,'color' => null,'created_at' => '2019-12-13 11:54:01','updated_at' => '2019-12-13 11:54:01'],
            ['id' => '12','name' => 'Bosna i Hercegovina','code' => 'bih','type' => 'countries','background_color' => null,'color' => null,'created_at' => '2019-12-13 12:11:10','updated_at' => '2020-02-18 15:45:29'],
            ['id' => '13','name' => 'Srbija','code' => 'srb','type' => 'countries','background_color' => null,'color' => null,'created_at' => '2019-12-13 12:11:18','updated_at' => '2019-12-13 12:11:18'],
            ['id' => '14','name' => 'SUPERMARKET','code' => '05-01','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2019-12-13 12:12:00','updated_at' => '2020-02-18 09:18:55'],
            ['id' => '15','name' => 'TEST 1','code' => '///','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2019-12-13 12:12:19','updated_at' => '2020-02-18 09:20:36'],
            ['id' => '16','name' => 'TEST','code' => 'warehouse','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2019-12-13 12:12:38','updated_at' => '2020-02-18 09:21:51'],
            ['id' => '17','name' => 'test','code' => 'building_industry','type' => 'client_categories','background_color' => null,'color' => null,'created_at' => '2019-12-13 12:13:50','updated_at' => '2020-02-18 09:22:13'],
            ['id' => '18','name' => 'AUTOINDUSTRIJA','code' => '01','type' => 'client_categories','background_color' => null,'color' => null,'created_at' => '2019-12-13 12:14:15','updated_at' => '2020-02-18 09:18:17'],
            ['id' => '19','name' => '30 dana','code' => '30_days_period','type' => 'payment_period','background_color' => null,'color' => null,'created_at' => '2019-12-27 10:37:06','updated_at' => '2019-12-27 10:37:06'],
            ['id' => '20','name' => '45 dana','code' => '45_days_period','type' => 'payment_period','background_color' => null,'color' => null,'created_at' => '2019-12-27 10:37:17','updated_at' => '2021-01-22 13:32:09'],
            ['id' => '21','name' => '50 dana','code' => '50_days_period','type' => 'payment_period','background_color' => null,'color' => null,'created_at' => '2019-12-27 10:37:31','updated_at' => '2019-12-27 10:37:31'],
            ['id' => '22','name' => '60 dana','code' => '60_days_period','type' => 'payment_period','background_color' => null,'color' => null,'created_at' => '2019-12-27 10:37:42','updated_at' => '2019-12-27 10:37:42'],
            ['id' => '23','name' => 'Virman','code' => 'wire_transfer_payment','type' => 'payment_type','background_color' => '#17a2b8','color' => '#ffffff','created_at' => '2019-12-27 10:38:26','updated_at' => '2019-12-27 10:38:26'],
            ['id' => '24','name' => 'Kartično plaćanje','code' => 'credit_card_payment','type' => 'payment_type','background_color' => '#ffc107','color' => '#ffffff','created_at' => '2019-12-27 10:38:50','updated_at' => '2019-12-27 10:38:50'],
            ['id' => '25','name' => 'Gotovina','code' => 'cash_payment','type' => 'payment_type','background_color' => '#28a745','color' => '#ffffff','created_at' => '2019-12-27 10:39:07','updated_at' => '2019-12-27 10:39:07'],
            ['id' => '26','name' => 'Prednarudžba','code' => 'preorder','type' => 'document_type','background_color' => '#7367f0','color' => '#ffffff','created_at' => '2020-01-23 13:43:32','updated_at' => '2020-02-13 13:20:54'],
            ['id' => '27','name' => 'Narudžba','code' => 'order','type' => 'document_type','background_color' => '#28a745','color' => '#ffffff','created_at' => '2020-01-23 13:43:43','updated_at' => '2020-02-13 13:21:18'],
            ['id' => '28','name' => 'Draft','code' => 'draft','type' => 'document_status','background_color' => '#adb5bd','color' => '#ffffff','created_at' => '2020-01-23 14:33:39','updated_at' => '2020-02-12 10:45:34'],
            ['id' => '29','name' => 'U obradi','code' => 'in_process','type' => 'document_status','background_color' => '#00cfe8','color' => '#ffffff','created_at' => '2020-01-23 14:33:54','updated_at' => '2020-02-12 10:47:47'],
            ['id' => '30','name' => 'Besplatna dostava','code' => 'free_delivery','type' => 'delivery_types','background_color' => null,'color' => null,'created_at' => '2020-01-24 17:26:42','updated_at' => '2020-01-24 17:26:42'],
            ['id' => '31','name' => 'Plaćena dostava','code' => 'paid_delivery','type' => 'delivery_types','background_color' => null,'color' => null,'created_at' => '2020-01-24 17:28:05','updated_at' => '2020-01-24 17:28:05'],
            ['id' => '32','name' => 'kom','code' => 'kom','type' => 'unit_types','background_color' => null,'color' => null,'created_at' => '2020-01-28 08:44:09','updated_at' => '2020-01-28 08:44:09'],
            ['id' => '33','name' => 'm2','code' => 'm2','type' => 'unit_types','background_color' => null,'color' => null,'created_at' => '2020-01-28 08:44:16','updated_at' => '2020-01-28 08:44:16'],
            ['id' => '34','name' => 'paket','code' => 'paket','type' => 'unit_types','background_color' => null,'color' => null,'created_at' => '2020-01-28 08:44:24','updated_at' => '2020-01-28 08:44:24'],
            ['id' => '35','name' => 'U skladištu','code' => 'in_warehouse','type' => 'document_status','background_color' => '#ff9f43','color' => '#ffffff','created_at' => '2020-01-28 09:09:27','updated_at' => '2020-02-12 10:48:11'],
            ['id' => '36','name' => 'Za fakturisanje','code' => 'for_invoicing','type' => 'document_status','background_color' => '#7367f0','color' => '#ffffff','created_at' => '2020-01-28 09:10:04','updated_at' => '2020-02-12 10:48:40'],
            ['id' => '37','name' => 'Fakturisano','code' => 'invoiced','type' => 'document_status','background_color' => '#28a745','color' => '#ffffff','created_at' => '2020-01-28 09:10:35','updated_at' => '2020-02-12 10:46:19'],
            ['id' => '38','name' => 'Otkazana','code' => 'canceled','type' => 'document_status','background_color' => '#dc3545','color' => '#ffffff','created_at' => '2020-01-28 09:11:21','updated_at' => '2020-02-12 10:47:21'],
            ['id' => '39','name' => 'Kompletiran','code' => 'completed','type' => 'document_status','background_color' => '#28a745','color' => '#ffffff','created_at' => '2020-01-29 13:30:38','updated_at' => '2020-02-12 10:45:59'],
            ['id' => '40','name' => 'Promo','code' => 'product_badge_promo','type' => 'product_badges','background_color' => '#00cfe8','color' => '#ffffff','created_at' => '2020-02-12 11:03:25','updated_at' => '2020-02-12 11:03:25'],
            ['id' => '41','name' => 'Akcija','code' => 'product_badge_hot','type' => 'product_badges','background_color' => '#dc3545','color' => '#ffffff','created_at' => '2020-02-12 11:03:52','updated_at' => '2020-02-12 11:03:52'],
            ['id' => '42','name' => 'Predračun','code' => 'offer','type' => 'document_type','background_color' => '#17a2b8','color' => '#ffffff','created_at' => '2020-02-13 13:22:10','updated_at' => '2020-04-03 10:17:48'],
            ['id' => '43','name' => 'Povrat','code' => 'return','type' => 'document_type','background_color' => '#ffc107','color' => '#ffffff','created_at' => '2020-02-13 13:24:56','updated_at' => '2020-02-13 13:24:56'],
            ['id' => '44','name' => 'Fokuser','code' => 'focuser_person','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2020-02-14 09:29:10','updated_at' => '2020-02-14 09:29:10'],
            ['id' => '45','name' => 'HIPERMARKET','code' => '05','type' => 'client_categories','background_color' => null,'color' => null,'created_at' => '2020-02-18 08:13:39','updated_at' => '2020-02-18 09:17:46'],
            ['id' => '46','name' => 'BENZINSKA PUMPA','code' => '01-01','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 08:16:13','updated_at' => '2020-02-18 09:18:33'],
            ['id' => '47','name' => 'GRAĐEVINA','code' => '02','type' => 'client_categories','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:22:38','updated_at' => '2020-02-18 09:22:38'],
            ['id' => '48','name' => 'AUTO DIJELOVI','code' => '01-02','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:23:33','updated_at' => '2020-02-18 09:23:33'],
            ['id' => '49','name' => 'AUTO SERVISI','code' => '01-03','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:23:45','updated_at' => '2020-02-18 09:23:45'],
            ['id' => '50','name' => 'AUTO SALONI','code' => '01-04','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:24:02','updated_at' => '2020-02-18 09:24:02'],
            ['id' => '51','name' => 'AUTO ELEKTRIKA','code' => '01-05','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:25:05','updated_at' => '2020-02-18 09:25:05'],
            ['id' => '52','name' => 'AUTO PRAONICE','code' => '01-06','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:25:43','updated_at' => '2020-02-18 09:25:43'],
            ['id' => '53','name' => 'VULKANIZERI','code' => '01-07','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:26:48','updated_at' => '2020-02-18 09:26:48'],
            ['id' => '54','name' => 'AUTO KLIMA CENTRI','code' => '01-08','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:28:03','updated_at' => '2020-02-18 09:28:03'],
            ['id' => '55','name' => 'BAU CENTRI','code' => '02-01','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:31:21','updated_at' => '2020-02-18 09:31:21'],
            ['id' => '56','name' => 'STOVARIŠTA','code' => '02-02','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:31:36','updated_at' => '2020-02-18 09:31:36'],
            ['id' => '57','name' => 'POLJOAPOTEKE','code' => '02-03','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:32:19','updated_at' => '2020-02-18 09:32:19'],
            ['id' => '58','name' => 'BOJE I LAKOVI','code' => '02-04','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:32:29','updated_at' => '2020-02-18 09:32:29'],
            ['id' => '59','name' => 'ŽELJEZARIJE/GVOŽĐARE','code' => '02-05','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:33:37','updated_at' => '2020-02-18 09:33:37'],
            ['id' => '60','name' => 'TRGOVINSKA RADNJA','code' => '06','type' => 'client_categories','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:37:46','updated_at' => '2020-02-18 09:37:46'],
            ['id' => '61','name' => 'NESPECIJALIZOVANE TRGOVINE','code' => '06-01','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-02-18 09:38:23','updated_at' => '2020-02-18 09:38:23'],
            ['id' => '62','name' => 'set','code' => 'set','type' => 'unit_types','background_color' => null,'color' => null,'created_at' => '2020-02-26 13:26:03','updated_at' => '2020-02-26 13:26:03'],
            ['id' => '63','name' => 'Na odobrenju','code' => 'pending','type' => 'status','background_color' => '#ffc107','color' => '#ffffff','created_at' => '2020-03-05 12:44:44','updated_at' => '2020-03-05 12:44:44'],
            ['id' => '64','name' => 'Marke (KM)','code' => 'KM','type' => 'currency','background_color' => null,'color' => null,'created_at' => '2020-03-05 17:22:37','updated_at' => '2020-03-05 17:26:49'],
            ['id' => '65','name' => 'Dinari (RSD)','code' => 'RSD','type' => 'currency','background_color' => null,'color' => null,'created_at' => '2020-03-05 17:22:46','updated_at' => '2020-03-05 17:26:40'],
            ['id' => '66','name' => 'Editor','code' => 'editor_person','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2020-03-09 16:03:58','updated_at' => '2020-03-09 16:03:58'],
            ['id' => '67','name' => 'Skladištar','code' => 'warehouse_person','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2020-03-09 16:05:56','updated_at' => '2020-03-09 16:05:56'],
            ['id' => '68','name' => 'Avansno plaćanje','code' => 'advance_payment','type' => 'payment_type','background_color' => '#7367f0','color' => '#ffffff','created_at' => '2020-04-03 10:15:47','updated_at' => '2020-04-03 10:15:47'],
            ['id' => '69','name' => 'Avans','code' => '00_days_period','type' => 'payment_period','background_color' => null,'color' => null,'created_at' => '2020-04-03 10:35:14','updated_at' => '2020-04-03 10:35:14'],
            ['id' => '70','name' => 'KRANJI KORISNIK - FIZIČKO LICE','code' => '04-01','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-04-09 09:47:17','updated_at' => '2020-04-09 09:47:17'],
            ['id' => '71','name' => 'KRAJNJI KORISNIK - FIZIČKO LICE','code' => '04','type' => 'client_categories','background_color' => null,'color' => null,'created_at' => '2020-04-09 09:48:11','updated_at' => '2020-04-09 09:48:24'],
            ['id' => '72','name' => 'KAM-BENZINSKA PUMPA','code' => '01-09','type' => 'client_location_types','background_color' => null,'color' => null,'created_at' => '2020-04-14 13:59:35','updated_at' => '2020-04-14 13:59:35'],
            ['id' => '73','name' => 'Lično preuzimanje','code' => 'personal_takeover','type' => 'delivery_types','background_color' => null,'color' => null,'created_at' => '2020-04-27 12:55:36','updated_at' => '2020-04-27 12:55:36'],
            ['id' => '75','name' => 'Agent prodaje','code' => 'sales_agent_person','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2020-07-15 09:37:54','updated_at' => '2020-07-15 09:41:13'],
            ['id' => '76','name' => 'Gotovina MP','code' => 'cash','type' => 'document_type','background_color' => '#000000','color' => '#ffffff','created_at' => '2020-07-15 16:52:29','updated_at' => '2020-07-15 16:52:29'],
            ['id' => '77','name' => '7 dana','code' => '7_days_period','type' => 'payment_period','background_color' => null,'color' => null,'created_at' => '2020-07-16 08:13:37','updated_at' => '2020-07-16 08:13:37'],
            ['id' => '78','name' => 'Snizena cijena','code' => 'product_badge_nisko','type' => 'product_badges','background_color' => '#000000','color' => '#ffffff','created_at' => '2020-09-14 13:16:10','updated_at' => '2020-09-14 13:16:10'],
            ['id' => '79','name' => 'Naplata','code' => 'office_naplata','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2020-09-14 14:31:28','updated_at' => '2020-09-14 14:31:28'],
            ['id' => '80','name' => 'Fokus','code' => 'product_badge_fokus','type' => 'product_badges','background_color' => '#0059ff','color' => '#000000','created_at' => '2021-01-18 15:57:26','updated_at' => '2021-01-18 16:02:26'],
            ['id' => '81','name' => 'Gratis proizvod','code' => 'gratis','type' => 'action_types','background_color' => null,'color' => null,'created_at' => '2021-02-24 11:04:44','updated_at' => '2021-02-24 11:04:44'],
            ['id' => '82','name' => 'Akcijski rabat','code' => 'discount','type' => 'action_types','background_color' => null,'color' => null,'created_at' => '2021-02-24 11:04:58','updated_at' => '2021-02-24 11:04:58'],
            ['id' => '83','name' => 'Brza pošta','code' => 'express_post','type' => 'document_status','background_color' => '#00cfe8','color' => '#ffffff','created_at' => '2021-03-31 08:26:07','updated_at' => '2021-03-31 08:26:07'],
            ['id' => '84','name' => 'Otpremljeno','code' => 'shipped','type' => 'document_status','background_color' => '#7367f0','color' => '#ffffff','created_at' => '2021-03-31 08:26:31','updated_at' => '2021-03-31 08:26:31'],
            ['id' => '85','name' => 'Dostavljeno','code' => 'delivered','type' => 'document_status','background_color' => '#28a745','color' => '#ffffff','created_at' => '2021-03-31 08:26:57','updated_at' => '2021-03-31 08:26:57'],
            ['id' => '86','name' => 'Vraćeno','code' => 'returned','type' => 'document_status','background_color' => '#dc3545','color' => '#ffffff','created_at' => '2021-03-31 08:27:22','updated_at' => '2021-03-31 08:27:22'],
            ['id' => '87','name' => 'Preuzeto','code' => 'retrieved','type' => 'document_status','background_color' => '#28a745','color' => '#ffffff','created_at' => '2021-03-31 08:27:44','updated_at' => '2021-03-31 08:27:44'],
            ['id' => '88','name' => 'Otkazana','code' => 'express_post_canceled','type' => 'document_status','background_color' => '#dc3545','color' => '#ffffff','created_at' => '2021-04-15 07:11:10','updated_at' => '2021-04-15 07:11:34'],
            ['id' => '89','name' => 'U procesu dostave','code' => 'express_post_in_process','type' => 'document_status','background_color' => '#00cfe8','color' => '#ffffff','created_at' => '2021-04-15 09:16:52','updated_at' => '2021-04-15 09:16:52'],
            ['id' => '90','name' => 'Plaćanje','code' => 'payment','type' => 'payment_therms','background_color' => null,'color' => null,'created_at' => '2021-05-10 15:08:57','updated_at' => '2021-05-10 15:08:57'],
            ['id' => '91','name' => 'Kompezacija','code' => 'compensation','type' => 'payment_therms','background_color' => null,'color' => null,'created_at' => '2021-05-10 15:09:09','updated_at' => '2021-05-10 15:09:09'],
            ['id' => '92','name' => 'Konsignacija','code' => 'consignment','type' => 'payment_therms','background_color' => null,'color' => null,'created_at' => '2021-05-10 15:09:22','updated_at' => '2021-05-10 15:09:22'],
            ['id' => '93','name' => 'Storno','code' => 'reversal','type' => 'document_type','background_color' => '#dc3545','color' => '#ffffff','created_at' => '2021-05-11 10:38:33','updated_at' => '2021-05-11 10:38:33'],
            ['id' => '94','name' => 'Stornirano','code' => 'reversed','type' => 'document_status','background_color' => '#dc3545','color' => '#ffffff','created_at' => '2021-05-11 11:39:53','updated_at' => '2021-05-11 11:39:53'],
            ['id' => '95','name' => 'Spremanje','code' => 'warehouse_preparing','type' => 'document_status','background_color' => '#ff9f43','color' => '#ffffff','created_at' => '2021-05-11 13:40:24','updated_at' => '2021-05-11 13:40:24'],
            ['id' => '96','name' => 'Blokiran','code' => 'blocked','type' => 'status','background_color' => '#dc3545','color' => '#ffffff','created_at' => '2021-06-04 10:25:03','updated_at' => '2021-06-04 10:25:03'],
            ['id' => '97','name' => 'Na odobrenju','code' => 'not_confirmed','type' => 'payment_status','background_color' => '#adb5bd','color' => '#ffffff','created_at' => '2021-06-28 11:23:36','updated_at' => '2021-06-28 11:23:36'],
            ['id' => '98','name' => 'Odobreno','code' => 'confirmed','type' => 'payment_status','background_color' => '#28a745','color' => '#ffffff','created_at' => '2021-06-28 11:23:57','updated_at' => '2021-06-28 11:23:57'],
            ['id' => '99','name' => 'Za sinhronizaciju','code' => 'for_sync','type' => 'sync_status','background_color' => '#adb5bd','color' => '#ffffff','created_at' => '2021-07-26 14:50:34','updated_at' => '2021-07-26 14:50:34'],
            ['id' => '100','name' => 'Sinhronizirano','code' => 'synchronized','type' => 'sync_status','background_color' => '#6cb168','color' => '#ffffff','created_at' => '2021-07-26 14:50:53','updated_at' => '2021-07-26 14:50:53'],
            ['id' => '101','name' => 'Neuspjela','code' => 'failed','type' => 'sync_status','background_color' => '#dd1d4d','color' => '#ffffff','created_at' => '2021-07-26 14:51:11','updated_at' => '2021-07-26 14:51:11'],
            ['id' => '102','name' => 'Odgovorna osoba na kupcu','code' => 'client_person','type' => 'person_types','background_color' => null,'color' => null,'created_at' => '2021-11-09 11:37:16','updated_at' => '2021-11-09 11:37:16'],
            ['id' => '103','name' => 'Gotovina','code' => 'payment_cash','type' => 'payment_categories','background_color' => null,'color' => null,'created_at' => '2021-11-09 11:51:32','updated_at' => '2021-11-09 11:51:32'],
            ['id' => '104','name' => 'Predračun','code' => 'payment_preinvoice','type' => 'payment_categories','background_color' => null,'color' => null,'created_at' => '2021-11-09 11:52:30','updated_at' => '2021-11-09 11:52:30'],
            ['id' => '105','name' => 'Predračun','code' => 'fund_preinvoice','type' => 'fund_sources','background_color' => null,'color' => null,'created_at' => '2021-11-09 11:53:02','updated_at' => '2021-11-09 11:53:02'],
            ['id' => '106','name' => 'Tekući račun','code' => 'fund_wire_transfer','type' => 'fund_sources','background_color' => null,'color' => null,'created_at' => '2021-11-09 11:54:01','updated_at' => '2021-11-09 11:54:01'],
            ['id' => '107','name' => 'Kompenzacije','code' => 'fund_compensation','type' => 'fund_sources','background_color' => null,'color' => null,'created_at' => '2021-11-09 11:54:35','updated_at' => '2021-11-09 11:54:35']
        ];
        DB::table('code_books')->insert($code_books);
    }
}
