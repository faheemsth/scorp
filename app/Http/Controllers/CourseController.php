<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\University;
use App\Models\CourseLevel;
use Illuminate\Http\Request;
use App\Models\CourseDuration;
use Illuminate\Support\Collection;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (\Auth::user()->can('manage courses') || \Auth::user()->type == 'super admin' ) {
            if(\Auth::user()->type == 'super admin'){
                $courses = Course::get();
            } else {
                $courses = Course::where(['created_by' => \Auth::user()->id])->get();
            }

            $users = User::get()->pluck('name', 'id');
           
            return view('course.index')->with(['courses' => $courses, 'users' => $users]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (\Auth::user()->can('create courses')) {


            $universities     = University::get()->pluck('name', 'id');
            $universities->prepend('Select University', '');

            $courselevel     = CourseLevel::get()->pluck('name', 'id');
            $courselevel->prepend('Select Course Level', '');

            $courseduration     = CourseDuration::get()->pluck('duration', 'id');
            $courseduration->prepend('Select Course Duration', '');


            $country_curr =new Collection(self::getCountryCurrency()); 
            $country_curr = $country_curr->pluck('name', 'code');
            $country_curr->prepend('Select Currency');

            
            return view('course.create', compact('universities', 'courselevel', 'courseduration', 'country_curr'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if (\Auth::user()->can('create courses')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:50',
                    'university_id' => 'required|max:20',
                    'courselevel_id' => 'required|max:20',
                    'courseduration_id' => 'required|max:20',
                    'currency' => 'required',
                    'fee' => 'required'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('course.index')->with('error', $messages->first());
            }

            $course              = new Course();
            $course->name        = $request->name;
            $course->university_id        = $request->university_id;
            $course->courselevel_id        = $request->courselevel_id;
            $course->courseduration_id        = $request->courseduration_id;
            $course->currency = $request->currency;
            $course->fee        = $request->fee;
            $course->created_by = \Auth::user()->id;
            $course->save();

            return redirect()->route('course.index')->with('success', __('Course successfully created!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
        return redirect()->route('course.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if (\Auth::user()->can('edit university')) {
            $course = Course::find($id);

            $universities     = University::get()->pluck('name', 'id');
            $universities->prepend('Select University', '');

            $courselevel     = CourseLevel::get()->pluck('name', 'id');
            $courselevel->prepend('Select Course Level', '');

            $courseduration     = CourseDuration::get()->pluck('duration', 'id');
            $courseduration->prepend('Select Course Duration', '');

            $country_curr =new Collection(self::getCountryCurrency()); 
            $country_curr = $country_curr->pluck('name', 'code');
            $country_curr->prepend('Select Currency');

            return view('course.edit', compact('course', 'universities', 'courselevel', 'courseduration', 'country_curr'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    private function getCountryCurrency(){

            $currency_list = array(
                array("name" => "Afghan Afghani", "code" => "AFA"),
                array("name" => "Albanian Lek", "code" => "ALL"),
                array("name" => "Algerian Dinar", "code" => "DZD"),
                array("name" => "Angolan Kwanza", "code" => "AOA"),
                array("name" => "Argentine Peso", "code" => "ARS"),
                array("name" => "Armenian Dram", "code" => "AMD"),
                array("name" => "Aruban Florin", "code" => "AWG"),
                array("name" => "Australian Dollar", "code" => "AUD"),
                array("name" => "Azerbaijani Manat", "code" => "AZN"),
                array("name" => "Bahamian Dollar", "code" => "BSD"),
                array("name" => "Bahraini Dinar", "code" => "BHD"),
                array("name" => "Bangladeshi Taka", "code" => "BDT"),
                array("name" => "Barbadian Dollar", "code" => "BBD"),
                array("name" => "Belarusian Ruble", "code" => "BYR"),
                array("name" => "Belgian Franc", "code" => "BEF"),
                array("name" => "Belize Dollar", "code" => "BZD"),
                array("name" => "Bermudan Dollar", "code" => "BMD"),
                array("name" => "Bhutanese Ngultrum", "code" => "BTN"),
                array("name" => "Bitcoin", "code" => "BTC"),
                array("name" => "Bolivian Boliviano", "code" => "BOB"),
                array("name" => "Bosnia-Herzegovina Convertible Mark", "code" => "BAM"),
                array("name" => "Botswanan Pula", "code" => "BWP"),
                array("name" => "Brazilian Real", "code" => "BRL"),
                array("name" => "British Pound Sterling", "code" => "GBP"),
                array("name" => "Brunei Dollar", "code" => "BND"),
                array("name" => "Bulgarian Lev", "code" => "BGN"),
                array("name" => "Burundian Franc", "code" => "BIF"),
                array("name" => "Cambodian Riel", "code" => "KHR"),
                array("name" => "Canadian Dollar", "code" => "CAD"),
                array("name" => "Cape Verdean Escudo", "code" => "CVE"),
                array("name" => "Cayman Islands Dollar", "code" => "KYD"),
                array("name" => "CFA Franc BCEAO", "code" => "XOF"),
                array("name" => "CFA Franc BEAC", "code" => "XAF"),
                array("name" => "CFP Franc", "code" => "XPF"),
                array("name" => "Chilean Peso", "code" => "CLP"),
                array("name" => "Chilean Unit of Account", "code" => "CLF"),
                array("name" => "Chinese Yuan", "code" => "CNY"),
                array("name" => "Colombian Peso", "code" => "COP"),
                array("name" => "Comorian Franc", "code" => "KMF"),
                array("name" => "Congolese Franc", "code" => "CDF"),
                array("name" => "Costa Rican Colón", "code" => "CRC"),
                array("name" => "Croatian Kuna", "code" => "HRK"),
                array("name" => "Cuban Convertible Peso", "code" => "CUC"),
                array("name" => "Czech Republic Koruna", "code" => "CZK"),
                array("name" => "Danish Krone", "code" => "DKK"),
                array("name" => "Djiboutian Franc", "code" => "DJF"),
                array("name" => "Dominican Peso", "code" => "DOP"),
                array("name" => "East Caribbean Dollar", "code" => "XCD"),
                array("name" => "Egyptian Pound", "code" => "EGP"),
                array("name" => "Eritrean Nakfa", "code" => "ERN"),
                array("name" => "Estonian Kroon", "code" => "EEK"),
                array("name" => "Ethiopian Birr", "code" => "ETB"),
                array("name" => "Euro", "code" => "EUR"),
                array("name" => "Falkland Islands Pound", "code" => "FKP"),
                array("name" => "Fijian Dollar", "code" => "FJD"),
                array("name" => "Gambian Dalasi", "code" => "GMD"),
                array("name" => "Georgian Lari", "code" => "GEL"),
                array("name" => "German Mark", "code" => "DEM"),
                array("name" => "Ghanaian Cedi", "code" => "GHS"),
                array("name" => "Gibraltar Pound", "code" => "GIP"),
                array("name" => "Greek Drachma", "code" => "GRD"),
                array("name" => "Guatemalan Quetzal", "code" => "GTQ"),
                array("name" => "Guinean Franc", "code" => "GNF"),
                array("name" => "Guyanaese Dollar", "code" => "GYD"),
                array("name" => "Haitian Gourde", "code" => "HTG"),
                array("name" => "Honduran Lempira", "code" => "HNL"),
                array("name" => "Hong Kong Dollar", "code" => "HKD"),
                array("name" => "Hungarian Forint", "code" => "HUF"),
                array("name" => "Icelandic Króna", "code" => "ISK"),
                array("name" => "Indian Rupee", "code" => "INR"),
                array("name" => "Indonesian Rupiah", "code" => "IDR"),
                array("name" => "Iranian Rial", "code" => "IRR"),
                array("name" => "Iraqi Dinar", "code" => "IQD"),
                array("name" => "Israeli New Sheqel", "code" => "ILS"),
                array("name" => "Italian Lira", "code" => "ITL"),
                array("name" => "Jamaican Dollar", "code" => "JMD"),
                array("name" => "Japanese Yen", "code" => "JPY"),
                array("name" => "Jordanian Dinar", "code" => "JOD"),
                array("name" => "Kazakhstani Tenge", "code" => "KZT"),
                array("name" => "Kenyan Shilling", "code" => "KES"),
                array("name" => "Kuwaiti Dinar", "code" => "KWD"),
                array("name" => "Kyrgystani Som", "code" => "KGS"),
                array("name" => "Laotian Kip", "code" => "LAK"),
                array("name" => "Latvian Lats", "code" => "LVL"),
                array("name" => "Lebanese Pound", "code" => "LBP"),
                array("name" => "Lesotho Loti", "code" => "LSL"),
                array("name" => "Liberian Dollar", "code" => "LRD"),
                array("name" => "Libyan Dinar", "code" => "LYD"),
                array("name" => "Litecoin", "code" => "LTC"),
                array("name" => "Lithuanian Litas", "code" => "LTL"),
                array("name" => "Macanese Pataca", "code" => "MOP"),
                array("name" => "Macedonian Denar", "code" => "MKD"),
                array("name" => "Malagasy Ariary", "code" => "MGA"),
                array("name" => "Malawian Kwacha", "code" => "MWK"),
                array("name" => "Malaysian Ringgit", "code" => "MYR"),
                array("name" => "Maldivian Rufiyaa", "code" => "MVR"),
                array("name" => "Mauritanian Ouguiya", "code" => "MRO"),
                array("name" => "Mauritian Rupee", "code" => "MUR"),
                array("name" => "Mexican Peso", "code" => "MXN"),
                array("name" => "Moldovan Leu", "code" => "MDL"),
                array("name" => "Mongolian Tugrik", "code" => "MNT"),
                array("name" => "Moroccan Dirham", "code" => "MAD"),
                array("name" => "Mozambican Metical", "code" => "MZM"),
                array("name" => "Myanmar Kyat", "code" => "MMK"),
                array("name" => "Namibian Dollar", "code" => "NAD"),
                array("name" => "Nepalese Rupee", "code" => "NPR"),
                array("name" => "Netherlands Antillean Guilder", "code" => "ANG"),
                array("name" => "New Taiwan Dollar", "code" => "TWD"),
                array("name" => "New Zealand Dollar", "code" => "NZD"),
                array("name" => "Nicaraguan Córdoba", "code" => "NIO"),
                array("name" => "Nigerian Naira", "code" => "NGN"),
                array("name" => "North Korean Won", "code" => "KPW"),
                array("name" => "Norwegian Krone", "code" => "NOK"),
                array("name" => "Omani Rial", "code" => "OMR"),
                array("name" => "Pakistani Rupee", "code" => "PKR"),
                array("name" => "Panamanian Balboa", "code" => "PAB"),
                array("name" => "Papua New Guinean Kina", "code" => "PGK"),
                array("name" => "Paraguayan Guarani", "code" => "PYG"),
                array("name" => "Peruvian Nuevo Sol", "code" => "PEN"),
                array("name" => "Philippine Peso", "code" => "PHP"),
                array("name" => "Polish Zloty", "code" => "PLN"),
                array("name" => "Qatari Rial", "code" => "QAR"),
                array("name" => "Romanian Leu", "code" => "RON"),
                array("name" => "Russian Ruble", "code" => "RUB"),
                array("name" => "Rwandan Franc", "code" => "RWF"),
                array("name" => "Salvadoran Colón", "code" => "SVC"),
                array("name" => "Samoan Tala", "code" => "WST"),
                array("name" => "São Tomé and Príncipe Dobra", "code" => "STD"),
                array("name" => "Saudi Riyal", "code" => "SAR"),
                array("name" => "Serbian Dinar", "code" => "RSD"),
                array("name" => "Seychellois Rupee", "code" => "SCR"),
                array("name" => "Sierra Leonean Leone", "code" => "SLL"),
                array("name" => "Singapore Dollar", "code" => "SGD"),
                array("name" => "Slovak Koruna", "code" => "SKK"),
                array("name" => "Solomon Islands Dollar", "code" => "SBD"),
                array("name" => "Somali Shilling", "code" => "SOS"),
                array("name" => "South African Rand", "code" => "ZAR"),
                array("name" => "South Korean Won", "code" => "KRW"),
                array("name" => "South Sudanese Pound", "code" => "SSP"),
                array("name" => "Special Drawing Rights", "code" => "XDR"),
                array("name" => "Sri Lankan Rupee", "code" => "LKR"),
                array("name" => "St. Helena Pound", "code" => "SHP"),
                array("name" => "Sudanese Pound", "code" => "SDG"),
                array("name" => "Surinamese Dollar", "code" => "SRD"),
                array("name" => "Swazi Lilangeni", "code" => "SZL"),
                array("name" => "Swedish Krona", "code" => "SEK"),
                array("name" => "Swiss Franc", "code" => "CHF"),
                array("name" => "Syrian Pound", "code" => "SYP"),
                array("name" => "Tajikistani Somoni", "code" => "TJS"),
                array("name" => "Tanzanian Shilling", "code" => "TZS"),
                array("name" => "Thai Baht", "code" => "THB"),
                array("name" => "Tongan Pa'anga", "code" => "TOP"),
                array("name" => "Trinidad & Tobago Dollar", "code" => "TTD"),
                array("name" => "Tunisian Dinar", "code" => "TND"),
                array("name" => "Turkish Lira", "code" => "TRY"),
                array("name" => "Turkmenistani Manat", "code" => "TMT"),
                array("name" => "Ugandan Shilling", "code" => "UGX"),
                array("name" => "Ukrainian Hryvnia", "code" => "UAH"),
                array("name" => "United Arab Emirates Dirham", "code" => "AED"),
                array("name" => "Uruguayan Peso", "code" => "UYU"),
                array("name" => "US Dollar", "code" => "USD"),
                array("name" => "Uzbekistan Som", "code" => "UZS"),
                array("name" => "Vanuatu Vatu", "code" => "VUV"),
                array("name" => "Venezuelan BolÃvar", "code" => "VEF"),
                array("name" => "Vietnamese Dong", "code" => "VND"),
                array("name" => "Yemeni Rial", "code" => "YER"),
                array("name" => "Zambian Kwacha", "code" => "ZMK"),
                array("name" => "Zimbabwean dollar", "code" => "ZWL")
            );
            return  $currency_list;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
        if (\Auth::user()->can('edit courses')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:50',
                    'university_id' => 'required|max:20',
                    'courselevel_id' => 'required|max:20',
                    'courseduration_id' => 'required|max:20',
                    'currency' => 'required',
                    'fee' => 'required'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('course.index')->with('error', $messages->first());
            }

            $course->name        = $request->name;
            $course->university_id        = $request->university_id;
            $course->courselevel_id        = $request->courselevel_id;
            $course->courseduration_id        = $request->courseduration_id;
            $course->currency = $request->currency;
            $course->fee        = $request->fee;
            $course->save();

            return redirect()->route('course.index')->with('success', __('Course successfully updated!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if(\Auth::user()->can('delete courses'))
        {
            Course::find($id)->delete();

            return redirect()->route('course.index')->with('success', __('Course successfully deleted!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
