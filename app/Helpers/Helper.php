<?php

namespace App\Helpers;

use App\Models\SearchRoute;
use App\Models\Currency;

class Helper
{
    public static function get_currencies()
    {
        return Currency::select('id', 'code')->get();
    }

    public static function get_roles()
    {
        $roles = ['staff', 'admin'];
        return $roles;
    }

    public static function get_debt_types()
    {
        $types = ['client', 'supplier'];
        return $types;
    }

    public static function get_route_names()
    {
        return SearchRoute::pluck('name')->toArray();
    }

    public static function convert($currency_id, $number)
    {
        return Currency::find($currency_id)->rate * $number;
    }

    public static function get_countries()
    {
        $countries = [
            "Afghanistan",
            "Albania",
            "Algeria",
            "American Samoa",
            "Andorra",
            "Angola",
            "Anguilla",
            "Antarctica",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Aruba",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bermuda",
            "Bhutan",
            "Bolivia",
            "Bosnia and Herzegovina",
            "Botswana",
            "Brazil",
            "Brunei Darussalam",
            "Bulgaria",
            "Burkina Faso",
            "Burundi",
            "Cabo Verde",
            "Cambodia",
            "Cameroon",
            "Canada",
            "Cayman Islands",
            "Central African Republic",
            "Chad",
            "Chile",
            "China",
            "Colombia",
            "Comoros",
            "Congo",
            "Congo (DRC)",
            "Costa Rica",
            "Côte d'Ivoire",
            "Croatia",
            "Cuba",
            "Cyprus",
            "Czechia",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Eswatini",
            "Ethiopia",
            "Fiji",
            "Finland",
            "France",
            "Gabon",
            "Gambia",
            "Georgia",
            "Germany",
            "Ghana",
            "Greece",
            "Grenada",
            "Guam",
            "Guatemala",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Honduras",
            "Hong Kong",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran",
            "Iraq",
            "Ireland",
            "Israel",
            "Italy",
            "Jamaica",
            "Japan",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Korea (North)",
            "Korea (South)",
            "Kuwait",
            "Kyrgyzstan",
            "Laos",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands",
            "Mauritania",
            "Mauritius",
            "Mexico",
            "Micronesia",
            "Moldova",
            "Monaco",
            "Mongolia",
            "Montenegro",
            "Morocco",
            "Mozambique",
            "Myanmar",
            "Namibia",
            "Nauru",
            "Nepal",
            "Netherlands",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "Norway",
            "Oman",
            "Pakistan",
            "Palau",
            "Palestine",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Poland",
            "Portugal",
            "Qatar",
            "Romania",
            "Russia",
            "Rwanda",
            "Samoa",
            "San Marino",
            "São Tomé and Príncipe",
            "Saudi Arabia",
            "Senegal",
            "Serbia",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "South Sudan",
            "Spain",
            "Sri Lanka",
            "Sudan",
            "Suriname",
            "Sweden",
            "Switzerland",
            "Syria",
            "Taiwan",
            "Tajikistan",
            "Tanzania",
            "Thailand",
            "Timor-Leste",
            "Togo",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
            "United Kingdom",
            "United States",
            "Uruguay",
            "Uzbekistan",
            "Vanuatu",
            "Venezuela",
            "Vietnam",
            "Yemen",
            "Zambia",
            "Zimbabwe",
        ];

        return $countries;
    }

    public static function get_cities()
    {
        $cities = [
            "Akkar (Halba)",
            "Baalbek",
            "Hermel",
            "Beirut",
            "Beqaa Governorate",
            "Rashaya",
            "Western Beqaa",
            "Zahlé",
            "Keserwan-Jbeil Governorate",
            "Byblos",
            "Keserwan",
            "Mount Lebanon Governorate (Baabda)",
            "Aley",
            "Baabda",
            "Chouf",
            "Matn",
            "Bint Jbeil",
            "Hasbaya",
            "Marjeyoun",
            "Nabatieh",
            "North Governorate",
            "Batroun",
            "Bsharri",
            "Koura",
            "Tripoli",
            "Zgharta",
            "South Governorate",
            "Sidon",
            "Jezzine",
            "Tyre",
        ];

        return $cities;
    }

    public static function get_expense_categories()
    {
        return ['Electricity', 'Water', 'Internet', 'Rent', 'Employee Salary', 'Maintenance', 'Miscellaneous'];
    }

    public static function get_order_statuses()
    {
        return ['unpaid', 'partially paid', 'paid'];
    }

    public static function get_payment_methods()
    {
        return ['Cash On Delivery', 'Whish', 'OMT', 'Credit/Debit Card', 'Bank Transfer'];
    }

    public static function get_client_statuses()
    {
        return ['active', 'inactive'];
    }

    public static function get_purchase_statuses()
    {
        return ['unpaid', 'partially paid', 'paid'];
    }
}
