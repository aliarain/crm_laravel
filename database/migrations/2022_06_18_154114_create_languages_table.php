<?php

use App\Models\Settings\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('native')->nullable();
            $table->tinyInteger('rtl')->default('0')->nullable();
            $table->tinyInteger('status')->default('1')->comment('1=active, 0=inactive')->nullable();
            $table->tinyInteger('json_exist')->default('0')->nullable();
            $table->tinyInteger('is_default')->default('0')->nullable();

            // extra for saas
            $table->timestamps();
        });

        $sql = "INSERT INTO `languages` (`code`, `name`, `native`, `rtl`, `status`, `json_exist`, `is_default`, `created_at`, `updated_at`) VALUES
                ('en', 'English', 'English', 0, 1, 0, 1, NULL, '2023-03-13 05:55:06'),
                ('ar', 'Arabic', 'العربية', 1, 1, 0, 0, NULL, NULL),
                ('es', 'Spanish', 'Español', 0, 1, 0, 0, NULL, NULL),
                ('bn', 'Bengali', 'বাংলা', 0, 0, 0, 0, NULL, NULL),
                ('af', 'Afrikaans', 'Afrikaans', 0, 0, 0, 0, NULL, NULL),
                ('am', 'Amharic', 'አማርኛ', 0, 0, 0, 0, NULL, NULL),
                ('ay', 'Aymara', 'Aymar', 0, 0, 0, 0, NULL, NULL),
                ('az', 'Azerbaijani', 'Azərbaycanca / آذربايجان', 0, 0, 0, 0, NULL, NULL),
                ('be', 'Belarusian', 'Беларуская', 0, 0, 0, 0, NULL, NULL),
                ('bg', 'Bulgarian', 'Български', 0, 0, 0, 0, NULL, NULL),
                ('bi', 'Bislama', 'Bislama', 0, 0, 0, 0, NULL, NULL),
                ('bs', 'Bosnian', 'Bosanski', 0, 0, 0, 0, NULL, NULL),
                ('ca', 'Catalan', 'Català', 0, 0, 0, 0, NULL, NULL),
                ('ch', 'Chamorro', 'Chamoru', 0, 0, 0, 0, NULL, NULL),
                ('cs', 'Czech', 'Česky', 0, 0, 0, 0, NULL, NULL),
                ('da', 'Danish', 'Dansk', 0, 0, 0, 0, NULL, NULL),
                ('de', 'German', 'Deutsch', 0, 0, 0, 0, NULL, NULL),
                ('dv', 'Divehi', 'ދިވެހިބަސް', 1, 0, 0, 0, NULL, NULL),
                ('dz', 'Dzongkha', 'ཇོང་ཁ', 0, 0, 0, 0, NULL, NULL),
                ('el', 'Greek', 'Ελληνικά', 0, 0, 0, 0, NULL, NULL),
                ('et', 'Estonian', 'Eesti', 0, 0, 0, 0, NULL, NULL),
                ('eu', 'Basque', 'Euskara', 0, 0, 0, 0, NULL, NULL),
                ('fa', 'Persian', 'فارسی', 1, 0, 0, 0, NULL, NULL),
                ('ff', 'Peul', 'Fulfulde', 0, 0, 0, 0, NULL, NULL),
                ('fi', 'Finnish', 'Suomi', 0, 0, 0, 0, NULL, NULL),
                ('fj', 'Fijian', 'Na Vosa Vakaviti', 0, 0, 0, 0, NULL, NULL),
                ('fo', 'Faroese', 'Føroyskt', 0, 0, 0, 0, NULL, NULL),
                ('fr', 'French', 'Français', 0, 0, 0, 0, NULL, NULL),
                ('ga', 'Irish', 'Gaeilge', 0, 0, 0, 0, NULL, NULL),
                ('gl', 'Galician', 'Galego', 0, 0, 0, 0, NULL, NULL),
                ('gn', 'Guarani', 'Avañe\'ẽ', 0, 0, 0, 0, NULL, NULL),
                ('gv', 'Manx', 'Gaelg', 0, 0, 0, 0, NULL, NULL),
                ('he', 'Hebrew', 'עברית', 1, 0, 0, 0, NULL, NULL),
                ('hi', 'Hindi', 'हिन्दी', 0, 0, 0, 0, NULL, NULL),
                ('hr', 'Croatian', 'Hrvatski', 0, 0, 0, 0, NULL, NULL),
                ('ht', 'Haitian', 'Krèyol ayisyen', 0, 0, 0, 0, NULL, NULL),
                ('hu', 'Hungarian', 'Magyar', 0, 0, 0, 0, NULL, NULL),
                ('hy', 'Armenian', 'Հայերեն', 0, 0, 0, 0, NULL, NULL),
                ('indo', 'Indonesian', 'Bahasa Indonesia', 0, 0, 0, 0, NULL, NULL),
                ('is', 'Icelandic', 'Íslenska', 0, 0, 0, 0, NULL, NULL),
                ('it', 'Italian', 'Italiano', 0, 0, 0, 0, NULL, NULL),
                ('ja', 'Japanese', '日本語', 0, 0, 0, 0, NULL, NULL),
                ('ka', 'Georgian', 'ქართული', 0, 0, 0, 0, NULL, NULL),
                ('kg', 'Kongo', 'KiKongo', 0, 0, 0, 0, NULL, NULL),
                ('kk', 'Kazakh', 'Қазақша', 0, 0, 0, 0, NULL, NULL),
                ('kl', 'Greenlandic', 'Kalaallisut', 0, 0, 0, 0, NULL, NULL),
                ('km', 'Cambodian', 'ភាសាខ្មែរ', 0, 0, 0, 0, NULL, NULL),
                ('ko', 'Korean', '한국어', 0, 0, 0, 0, NULL, NULL),
                ('ku', 'Kurdish', 'Kurdî / كوردی', 1, 0, 0, 0, NULL, NULL),
                ('ky', 'Kirghiz', 'Kırgızca / Кыргызча', 0, 0, 0, 0, NULL, NULL),
                ('la', 'Latin', 'Latina', 0, 0, 0, 0, NULL, NULL),
                ('lb', 'Luxembourgish', 'Lëtzebuergesch', 0, 0, 0, 0, NULL, NULL),
                ('ln', 'Lingala', 'Lingála', 0, 0, 0, 0, NULL, NULL),
                ('lo', 'Laotian', 'ລາວ / Pha xa lao', 0, 0, 0, 0, NULL, NULL),
                ('lt', 'Lithuanian', 'Lietuvių', 0, 0, 0, 0, NULL, NULL),
                ('lu', 'Luxembourg', 'Luxembourg', 0, 0, 0, 0, NULL, NULL),
                ('lv', 'Latvian', 'Latviešu', 0, 0, 0, 0, NULL, NULL),
                ('mg', 'Malagasy', 'Malagasy', 0, 0, 0, 0, NULL, NULL),
                ('mh', 'Marshallese', 'Kajin Majel / Ebon', 0, 0, 0, 0, NULL, NULL),
                ('mi', 'Maori', 'Māori', 0, 0, 0, 0, NULL, NULL),
                ('mk', 'Macedonian', 'Македонски', 0, 0, 0, 0, NULL, NULL),
                ('mn', 'Mongolian', 'Монгол', 0, 0, 0, 0, NULL, NULL),
                ('ms', 'Malay', 'Bahasa Melayu', 0, 0, 0, 0, NULL, NULL),
                ('mt', 'Maltese', 'bil-Malti', 0, 0, 0, 0, NULL, NULL),
                ('my', 'Burmese', 'မြန်မာစာ', 0, 0, 0, 0, NULL, NULL),
                ('na', 'Nauruan', 'Dorerin Naoero', 0, 0, 0, 0, NULL, NULL),
                ('nb', 'Bokmål', 'Bokmål', 0, 0, 0, 0, NULL, NULL),
                ('nd', 'North Ndebele', 'Sindebele', 0, 0, 0, 0, NULL, NULL),
                ('ne', 'Nepali', 'नेपाली', 0, 0, 0, 0, NULL, NULL),
                ('nl', 'Dutch', 'Nederlands', 0, 0, 0, 0, NULL, NULL),
                ('nn', 'Norwegian Nynorsk', 'Norsk (nynorsk)', 0, 0, 0, 0, NULL, NULL),
                ('no', 'Norwegian', 'Norsk (bokmål / riksmål)', 0, 0, 0, 0, NULL, NULL),
                ('nr', 'South Ndebele', 'isiNdebele', 0, 0, 0, 0, NULL, NULL),
                ('ny', 'Chichewa', 'Chi-Chewa', 0, 0, 0, 0, NULL, NULL),
                ('oc', 'Occitan', 'Occitan', 0, 0, 0, 0, NULL, NULL),
                ('pa', 'Panjabi / Punjabi', 'ਪੰਜਾਬੀ / पंजाबी / پنجابي', 0, 0, 0, 0, NULL, NULL),
                ('pl', 'Polish', 'Polski', 0, 0, 0, 0, NULL, NULL),
                ('ps', 'Pashto', 'پښتو', 1, 0, 0, 0, NULL, NULL),
                ('pt', 'Portuguese', 'Português', 0, 0, 0, 0, NULL, NULL),
                ('qu', 'Quechua', 'Runa Simi', 0, 0, 0, 0, NULL, NULL),
                ('rn', 'Kirundi', 'Kirundi', 0, 0, 0, 0, NULL, NULL),
                ('ro', 'Romanian', 'Română', 0, 0, 0, 0, NULL, NULL),
                ('ru', 'Russian', 'Русский', 0, 0, 0, 0, NULL, NULL),
                ('rw', 'Rwandi', 'Kinyarwandi', 0, 0, 0, 0, NULL, NULL),
                ('sg', 'Sango', 'Sängö', 0, 0, 0, 0, NULL, NULL),
                ('si', 'Sinhalese', 'සිංහල', 0, 0, 0, 0, NULL, NULL),
                ('sk', 'Slovak', 'Slovenčina', 0, 0, 0, 0, NULL, NULL),
                ('sl', 'Slovenian', 'Slovenščina', 0, 0, 0, 0, NULL, NULL),
                ('sm', 'Samoan', 'Gagana Samoa', 0, 0, 0, 0, NULL, NULL),
                ('sn', 'Shona', 'chiShona', 0, 0, 0, 0, NULL, NULL),
                ('so', 'Somalia', 'Soomaaliga', 0, 0, 0, 0, NULL, NULL),
                ('sq', 'Albanian', 'Shqip', 0, 0, 0, 0, NULL, NULL),
                ('sr', 'Serbian', 'Српски', 0, 0, 0, 0, NULL, NULL),
                ('ss', 'Swati', 'SiSwati', 0, 0, 0, 0, NULL, NULL),
                ('st', 'Southern Sotho', 'Sesotho', 0, 0, 0, 0, NULL, NULL),
                ('sv', 'Swedish', 'Svenska', 0, 0, 0, 0, NULL, NULL),
                ('sw', 'Swahili', 'Kiswahili', 0, 0, 0, 0, NULL, NULL),
                ('ta', 'Tamil', 'தமிழ்', 0, 0, 0, 0, NULL, NULL),
                ('tg', 'Tajik', 'Тоҷикӣ', 0, 0, 0, 0, NULL, NULL),
                ('th', 'Thai', 'ไทย / Phasa Thai', 0, 0, 0, 0, NULL, NULL),
                ('ti', 'Tigrinya', 'ትግርኛ', 0, 0, 0, 0, NULL, NULL),
                ('tk', 'Turkmen', 'Туркмен /تركمن ', 0, 0, 0, 0, NULL, NULL),
                ('tn', 'Tswana', 'Setswana', 0, 0, 0, 0, NULL, NULL),
                ('to', 'Tonga', 'Lea Faka-Tonga', 0, 0, 0, 0, NULL, NULL),
                ('tr', 'Turkish', 'Türkçe', 0, 0, 0, 0, NULL, NULL),
                ('ts', 'Tsonga', 'Xitsonga', 0, 0, 0, 0, NULL, NULL),
                ('uk', 'Ukrainian', 'Українська', 0, 0, 0, 0, NULL, NULL),
                ('ur', 'Urdu', 'اردو', 1, 0, 0, 0, NULL, NULL),
                ('uz', 'Uzbek', 'Ўзбек', 0, 0, 0, 0, NULL, NULL),
                ('ve', 'Venda', 'Tshivenḓa', 0, 0, 0, 0, NULL, NULL),
                ('vi', 'Vietnamese', 'Tiếng Việt', 0, 0, 0, 0, NULL, NULL),
                ('xh', 'Xhosa', 'isiXhosa', 0, 0, 0, 0, NULL, NULL),
                ('zh', 'Chinese', '中文', 0, 0, 0, 0, NULL, NULL),
                ('zu', 'Zulu', 'isiZulu', 0, 0, 0, 0, NULL, NULL)";
        DB::statement($sql);

        $english = Language::find(1);
        $english->status = 1;
        $english->is_default = 1;
        $english->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}