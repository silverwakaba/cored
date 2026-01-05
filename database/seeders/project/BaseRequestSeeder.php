<?php

namespace Database\Seeders\Project;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Core\BaseRequest;
use App\Models\Core\BaseModule;

class BaseRequestSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        // Get module IDs
        // $paymentStatusModule = BaseModule::where('name', 'Payment Status')->first();
        $businessEntityModule = BaseModule::where('name', 'Business Entity')->first();
        $bankModule = BaseModule::where('name', 'Bank')->first();
        $currencyModule = BaseModule::where('name', 'Currency')->first();
        $qualificationModule = BaseModule::where('name', 'Qualification')->first();
        $workTypeModule = BaseModule::where('name', 'Work Type')->first();
        $taxTypeModule = BaseModule::where('name', 'Tax Type')->first();

        BaseRequest::insert([
            // // Payment Status
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Draft",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Posted",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Revised",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Process",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Closed",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Declined",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Verified",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Canceled",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Paid",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Unpaid",
            // ],
            // [
            //     'base_modules_id'   => $paymentStatusModule->id,
            //     'name'              => "Pending",
            // ],

            // Business Entity
            [
                'base_modules_id'   => $businessEntityModule->id,
                'name'              => "PT",
            ],
            [
                'base_modules_id'   => $businessEntityModule->id,
                'name'              => "CV",
            ],
            [
                'base_modules_id'   => $businessEntityModule->id,
                'name'              => "Firma",
            ],

            // Bank
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Central Asia (BCA)",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Mandiri",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Rakyat Indonesia (BRI)",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Negara Indonesia (BNI)",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Tabungan Negara (BTN)",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Danamon",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank CIMB Niaga",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Permata",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Panin",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Maybank Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank OCBC NISP",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Mega",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank BTPN",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Jago",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Syariah Indonesia (BSI)",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank DBS Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank HSBC Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank ANZ Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank UOB Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank BCA Syariah",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank BRI Syariah",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank BNI Syariah",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Muamalat",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Mega Syariah",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank BJB",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank BPD DIY",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank DKI",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Jateng",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Jatim",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Sumut",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Nagari",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Kaltimtara",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Kalsel",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Sultra",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Sulselbar",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank NTB Syariah",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Aceh",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Papua",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Ina Perdana",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Mestika Dharma",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Victoria International",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank SBI Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Ganesha",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Artos Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Harda Internasional",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Maspion Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Bisnis Internasional",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Mayora",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Index Selindo",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank MNC Internasional",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Bumi Arta",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Ekonomi Raharja",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Fama Internasional",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Mandiri Taspen",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank QNB Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Woori Saudara",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Shinhan Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Commonwealth",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Capital Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank BNP Paribas Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank ICBC Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Wokee Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank China Construction Bank Indonesia (CCB)",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Resona Perdania",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Mizuho Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank MUFG Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Artha Graha Internasional",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Multiarta Sentosa (MAS)",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Nationalnobu",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Prima Master",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Sahabat Sampoerna",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Seabank Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank KB Bukopin",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Mayapada",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Bukopin",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Agris",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank BRI Agroniaga",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Sinar Harapan Bali",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Jasa Jakarta",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Dinar Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Mestika Dharma",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Maspion",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Yudha Bhakti",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Hana Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank BCA Digital (formerly Bank Royal)",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Neo Commerce",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Allo Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Seabank Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Digital BJB",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank IBK Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Chinatrust Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Amar Indonesia",
            ],
            [
                'base_modules_id'   => $bankModule->id,
                'name'              => "Bank Krom Indonesia",
            ],

            // Currency
            [
                'base_modules_id'   => $currencyModule->id,
                'name'              => "IDR",
            ],
            [
                'base_modules_id'   => $currencyModule->id,
                'name'              => "USD",
            ],

            // Qualification
            [
                'base_modules_id'   => $qualificationModule->id,
                'name'              => "Kecil",
            ],
            [
                'base_modules_id'   => $qualificationModule->id,
                'name'              => "Menengah",
            ],
            [
                'base_modules_id'   => $qualificationModule->id,
                'name'              => "Besar",
            ],

            // Work Type
            [
                'base_modules_id'   => $workTypeModule->id,
                'name'              => "Jasa",
            ],
            [
                'base_modules_id'   => $workTypeModule->id,
                'name'              => "Sewa",
            ],
            [
                'base_modules_id'   => $workTypeModule->id,
                'name'              => "Barang",
            ],
            [
                'base_modules_id'   => $workTypeModule->id,
                'name'              => "Lainnya",
            ],

            // Tax Type
            [
                'base_modules_id'   => $taxTypeModule->id,
                'name'              => "Tanpa PPh",
            ],
            [
                'base_modules_id'   => $taxTypeModule->id,
                'name'              => "PPh Pasal 23",
            ],
            [
                'base_modules_id'   => $taxTypeModule->id,
                'name'              => "PPh Pasal 4 Ayat (2)",
            ],
        ]);
    }
}
