<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Settings\Currency;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('symbol')->nullable()->collation('utf8mb4_unicode_ci');
            $table->double('exchange_rate')->nullable();

            $table->timestamps();
        });
        $currencies = [
            [1, 'Leke', 'ALL', 'Lek','1.00'],
            [2, 'Dollars', 'USD', '$','1.00'],
            [3, 'Afghanis', 'AFN', '؋','1.00'],
            [4, 'Pesos', 'ARS', '$','1.00'],
            [5, 'Guilders', 'AWG', 'ƒ','1.00'],
            [6, 'Dollars', 'AUD', '$','1.00'],
            [7, 'New Manats', 'AZN', 'ман','1.00'],
            [8, 'Dollars', 'BSD', '$','1.00'],
            [9, 'Dollars', 'BBD', '$','1.00'],
            [10, 'Rubles', 'BYR', 'p.','1.00'],
            [11, 'Euro', 'EUR', '€','1.00'],
            [12, 'Dollars', 'BZD', 'BZ$','1.00'],
            [13, 'Dollars', 'BMD', '$','1.00'],
            [14, 'Bolivianos', 'BOB', '$b','1.00'],
            [15, 'Convertible Marka', 'BAM', 'KM','1.00'],
            [16, 'Pula', 'BWP', 'P','1.00'],
            [17, 'Leva', 'BGN', 'лв','1.00'],
            [18, 'Reais', 'BRL', 'R$','1.00'],
            [19, 'Pounds', 'GBP', '£','1.00'],
            [20, 'Dollars', 'BND', '$','1.00'],
            [21, 'Riels', 'KHR', '៛','1.00'],
            [22, 'Dollars', 'CAD', '$','1.00'],
            [23, 'Dollars', 'KYD', '$','1.00'],
            [24, 'Pesos', 'CLP', '$','1.00'],
            [25, 'Yuan Renminbi', 'CNY', '¥','1.00'],
            [26, 'Pesos', 'COP', '$','1.00'],
            [27, 'Colón', 'CRC', '₡','1.00'],
            [28, 'Kuna', 'HRK', 'kn','1.00'],
            [29, 'Pesos', 'CUP', '₱','1.00'],
            [30, 'Koruny', 'CZK', 'Kč','1.00'],
            [31, 'Kroner', 'DKK', 'kr','1.00'],
            [32, 'Pesos', 'DOP ', 'RD$','1.00'],
            [33, 'Dollars', 'XCD', '$','1.00'],
            [34, 'Pounds', 'EGP', '£','1.00'],
            [35, 'Colones', 'SVC', '$','1.00'],
            [36, 'Pounds', 'FKP', '£','1.00'],
            [37, 'Dollars', 'FJD', '$','1.00'],
            [38, 'Cedis', 'GHC', '¢','1.00'],
            [39, 'Pounds', 'GIP', '£','1.00'],
            [40, 'Quetzales', 'GTQ', 'Q','1.00'],
            [41, 'Pounds', 'GGP', '£','1.00'],
            [42, 'Dollars', 'GYD', '$','1.00'],
            [43, 'Lempiras', 'HNL', 'L','1.00'],
            [44, 'Dollars', 'HKD', '$','1.00'],
            [45, 'Forint', 'HUF', 'Ft','1.00'],
            [46, 'Kronur', 'ISK', 'kr','1.00'],
            [47, 'Rupees', 'INR', '₹','1.00'],
            [48, 'Rupiahs', 'IDR', 'Rp','1.00'],
            [49, 'Rials', 'IRR', '﷼','1.00'],
            [50, 'Pounds', 'IMP', '£','1.00'],
            [51, 'New Shekels', 'ILS', '₪','1.00'],
            [52, 'Dollars', 'JMD', 'J$','1.00'],
            [53, 'Yen', 'JPY', '¥','1.00'],
            [54, 'Pounds', 'JEP', '£','1.00'],
            [55, 'Tenge', 'KZT', 'лв','1.00'],
            [56, 'Won', 'KPW', '₩','1.00'],
            [57, 'Won', 'KRW', '₩','1.00'],
            [58, 'Soms', 'KGS', 'лв','1.00'],
            [59, 'Kips', 'LAK', '₭','1.00'],
            [60, 'Lati', 'LVL', 'Ls','1.00'],
            [61, 'Pounds', 'LBP', '£','1.00'],
            [62, 'Dollars', 'LRD', '$','1.00'],
            [63, 'Switzerland Francs', 'CHF', 'CHF','1.00'],
            [64, 'Litai', 'LTL', 'Lt','1.00'],
            [65, 'Denars', 'MKD', 'ден','1.00'],
            [66, 'Ringgits', 'MYR', 'RM','1.00'],
            [67, 'Rupees', 'MUR', '₨','1.00'],
            [68, 'Pesos', 'MXN', '$','1.00'],
            [69, 'Tugriks', 'MNT', '₮','1.00'],
            [70, 'Meticais', 'MZN', 'MT','1.00'],
            [71, 'Dollars', 'NAD', '$','1.00'],
            [72, 'Rupees', 'NPR', '₨','1.00'],
            [73, 'Guilders', 'ANG', 'ƒ','1.00'],
            [74, 'Dollars', 'NZD', '$','1.00'],
            [75, 'Cordobas', 'NIO', 'C$','1.00'],
            [76, 'Nairas', 'NGN', '₦','1.00'],
            [77, 'Krone', 'NOK', 'kr','1.00'],
            [78, 'Rials', 'OMR', '﷼','1.00'],
            [79, 'Rupees', 'PKR', '₨','1.00'],
            [80, 'Balboa', 'PAB', 'B/.','1.00'],
            [81, 'Guarani', 'PYG', 'Gs','1.00'],
            [82, 'Nuevos Soles', 'PEN', 'S/.','1.00'],
            [83, 'Pesos', 'PHP', 'Php','1.00'],
            [84, 'Zlotych', 'PLN', 'zł','1.00'],
            [85, 'Rials', 'QAR', '﷼','1.00'],
            [86, 'New Lei', 'RON', 'lei','1.00'],
            [87, 'Rubles', 'RUB', 'руб','1.00'],
            [88, 'Pounds', 'SHP', '£','1.00'],
            [89, 'Riyals', 'SAR', '﷼','1.00'],
            [90, 'Dinars', 'RSD', 'Дин.','1.00'],
            [91, 'Rupees', 'SCR', '₨','1.00'],
            [92, 'Dollars', 'SGD', '$','1.00'],
            [93, 'Dollars', 'SBD', '$','1.00'],
            [94, 'Shillings', 'SOS', 'S','1.00'],
            [95, 'Rand', 'ZAR', 'R','1.00'],
            [96, 'Rupees', 'LKR', '₨','1.00'],
            [97, 'Kronor', 'SEK', 'kr','1.00'],
            [98, 'Dollars', 'SRD', '$','1.00'],
            [99, 'Pounds', 'SYP', '£','1.00'],
            [100, 'New Dollars', 'TWD', 'NT$','1.00'],
            [101, 'Baht', 'THB', '฿','1.00'],
            [102, 'Dollars', 'TTD', 'TT$','1.00'],
            [103, 'Lira', 'TRY', 'TL','1.00'],
            [104, 'Liras', 'TRL', '£','1.00'],
            [105, 'Dollars', 'TVD', '$','1.00'],
            [106, 'Hryvnia', 'UAH', '₴','1.00'],
            [107, 'Pesos', 'UYU', '$U','1.00'],
            [108, 'Sums', 'UZS', 'лв','1.00'],
            [109, 'Bolivares Fuertes', 'VEF', 'Bs','1.00'],
            [110, 'Dong', 'VND', '₫','1.00'],
            [111, 'Rials', 'YER', '﷼','1.00'],
            [112, 'Taka', 'BDT', '৳','1.00'],
            [113, 'Zimbabwe Dollars', 'ZWD', 'Z$','1.00'],
            [114, 'Kenya', 'KES', 'KSh','1.00'],
            [115, 'Nigeria', 'naira', '₦','1.00'],
            [116, 'Ghana', 'GHS', 'GH₵','1.00'], 
            [117, 'Ethiopian', 'ETB', 'Br','1.00'],
            [118, 'Tanzania', 'TZS', 'TSh','1.00'],
            [119, 'Uganda', 'UGX', 'USh','1.00'], 
            [120, 'Rwandan', 'FRW', 'FRw','1.00'] 
        ];
        
        foreach ($currencies as $currency) {
            $store = new Currency();
            $store->id = $currency[0];
            $store->name = $currency[1];
            $store->code = $currency[2];
            $store->symbol = $currency[3];
            $store->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
