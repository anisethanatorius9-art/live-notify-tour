<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminAccessKey;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminRegisterController extends Controller
{
    public function show(): View
    {
        $countries = [
            '' => 'Select Country',
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BR' => 'Brazil',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'CV' => 'Cabo Verde',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CD' => 'Congo, Democratic Republic of the',
            'CG' => 'Congo, Republic of the',
            'CR' => 'Costa Rica',
            'CI' => 'Côte d\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'SZ' => 'Eswatini',
            'ET' => 'Ethiopia',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GR' => 'Greece',
            'GD' => 'Grenada',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HN' => 'Honduras',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea, North',
            'KR' => 'Korea, South',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'MK' => 'North Macedonia',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestine',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'QA' => 'Qatar',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'SS' => 'South Sudan',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        ];

        $organizations = [
            '' => 'Select Organization Type',
            'tour_operator' => 'Tour Operator / Safari Company',
            'hotel_resort' => 'Hotel / Resort / Lodge',
            'national_park' => 'National Park / Wildlife Sanctuary',
            'eco_lodge' => 'Eco-Lodge / Conservation Camp',
            'accommodation_provider' => 'Accommodation Provider',
            'travel_agency' => 'Travel Agency / Booking Office',
            'cultural_tourism_site' => 'Cultural Tourism Site / Heritage',
            'wildlife_conservancy' => 'Wildlife Conservancy / NGO',
            'safari_camp' => 'Safari Camp / Tented Camp',
            'adventure_travel' => 'Adventure Travel Company',
            'destination_marketing' => 'Destination Marketing Organization',
            'transport_service' => 'Transport & Transfer Service',
            'cruise_boat' => 'Cruise / Boat Safari Operator',
            'campground' => 'Campground / Campsite',
            'holiday_villa' => 'Holiday Villa / Apartment',
            'restaurant' => 'Restaurant / Dining Experience',
            'other' => 'Other Tourism / Hospitality Organization',
        ];

        return view('livewire.auth.admin-register', [
            'countries' => $countries,
            'organizations' => $organizations,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'organization' => ['required', 'string', 'max:255'],
            'admin_code' => ['nullable', 'string'],
        ]);

        if (! empty($data['admin_code'])) {
            if ($data['admin_code'] !== config('admin.registration_code')) {
                throw ValidationException::withMessages([
                    'admin_code' => __('The admin verification code is invalid.'),
                ]);
            }

            $otp = (string) random_int(100000, 999999);
            Log::info('Admin registration verification SMS', [
                'phone' => $data['phone'],
                'code' => $otp,
            ]);

            $request->session()->put('admin_registration', [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'country' => $data['country'],
                'phone' => $data['phone'],
                'organization' => $data['organization'],
                'role' => 'admin',
                'role_selected_at' => now(),
            ]);

            $request->session()->put('admin_registration_otp', $otp);
            $request->session()->put('admin_registration_otp_expires_at', now()->addMinutes(15)->timestamp);
            $request->session()->flash('status', __('A verification code has been sent via SMS to :phone.', ['phone' => $data['phone']]));

            return redirect()->route('admin.register.verify');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'country' => $data['country'],
            'phone' => $data['phone'],
            'organization' => $data['organization'],
            'role' => 'admin',
            'role_selected_at' => now(),
        ]);

        $accessKey = AdminAccessKey::create([
            'user_id' => $user->id,
            'access_key' => hash('sha256', random_bytes(32)),
            'is_active' => true,
            'expires_at' => now()->addMonths(config('admin.access_key.expiry_months', 6)),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->flash('access_key_generated', true);
        $request->session()->put('generated_access_key', $accessKey->access_key);

        return redirect()->route('dashboard.admin');
    }
}
