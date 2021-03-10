<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\System\Models\Organisation;

class OrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF BIRMINGHAM',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'Edgbaston',
            'city' => 'Birmingham',
            'postcode' => 'B15 2TT',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF BRISTOL',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'BEACON HOUSE',
            'street' => 'QUEENS ROAD',
            'address_line_2' => '',
            'county' => 'BRISTOL',
            'city' => 'BRISTOL',
            'postcode' => 'BS8 1QU',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF CAMBRIDGE',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'THE OLD SCHOOLS',
            'street' => 'TRINITY LANE',
            'address_line_2' => '',
            'county' => 'CAMBRIDGESHIRE',
            'city' => 'CAMBRIDGE',
            'postcode' => 'CB2 1TN',
        ]);

        Organisation::create([
            'organisation_name' => 'CARDIFF UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'WALES',
            'city' => 'CARDIFF',
            'postcode' => 'CF10 3AT',
        ]);

        Organisation::create([
            'organisation_name' => 'DURHAM UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'THE PALATINE CENTRE',
            'street' => 'STOCKTON ROAD',
            'address_line_2' => '',
            'county' => 'DURHAM',
            'city' => 'DURHAM',
            'postcode' => 'DH1 3LE',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF EDINBURGH',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'OLD COLLEGE',
            'street' => 'SOUTH BRIDGE',
            'address_line_2' => '',
            'county' => 'EDINBURGH',
            'city' => 'EDINBURGH',
            'postcode' => 'EH8 9YL',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF EXETER',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'NORTHCOTE HOUSE',
            'street' => 'THE QUEEN’S DRIVE',
            'address_line_2' => '',
            'county' => 'EXETER',
            'city' => 'EXETER',
            'postcode' => 'EX4 4QJ',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF GLASGOW',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '1 UNIVERSITY AVENUE',
            'street' => '',
            'address_line_2' => '',
            'county' => 'GLASGOW',
            'city' => 'GLASGOW',
            'postcode' => 'G12 8QQ',
        ]);

        Organisation::create([
            'organisation_name' => 'IMPERIAL COLLEGE LONDON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'SOUTH KENSINGTON CAMPUS',
            'street' => '',
            'address_line_2' => '',
            'county' => 'LONDON',
            'city' => 'LONDON',
            'postcode' => 'SW7 2AZ',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF LEEDS',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'E C STONER BUILDING',
            'street' => '11.72',
            'address_line_2' => '',
            'county' => 'LEEDS',
            'city' => 'LEEDS',
            'postcode' => 'LS2 9JT',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF LIVERPOOL',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'FOUNDATION BUILDING',
            'street' => 'BROWNLOW HILL',
            'address_line_2' => '',
            'county' => 'LIVERPOOL',
            'city' => 'LIVERPOOL',
            'postcode' => 'L69 7ZX',
        ]);

        Organisation::create([
            'organisation_name' => 'LONDON SCHOOL OF ECONOMICS & POLITICAL SCIENCE',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'HOUGHTON STREET',
            'address_line_2' => '',
            'county' => 'LONDON',
            'city' => 'LONDON',
            'postcode' => 'WC2A 2AE',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF MANCHESTER',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'OXFORD ROAD',
            'address_line_2' => '',
            'county' => 'MANCHESTER',
            'city' => 'MANCHESTER',
            'postcode' => 'M13 9PL',
        ]);

        Organisation::create([
            'organisation_name' => 'NEWCASTLE UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'TYNE AND WEAR',
            'city' => 'NEWCASTLE UPON TYNE',
            'postcode' => 'NE1 7RU',    
        ]);

		Organisation::create([
            'organisation_name' => 'UNIVERSITY OF NOTTINGHAM',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'A107 TRENT BUILDING',
            'street' => 'UNIVERSITY PARK',
            'address_line_2' => '',
            'county' => 'NOTTINGHAM',
            'city' => 'NOTTINGHAM',
            'postcode' => 'NG7 2RD',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF OXFORD',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'UNIVERSITY OFFICES',
            'street' => 'WELLINGTON SQUARE',
            'address_line_2' => '',
            'county' => 'OXFORD',
            'city' => 'OXFORD',
            'postcode' => 'OX1 2JD',
        ]);

        Organisation::create([
            'organisation_name' => 'QUEEN MARY UNIVERSITY OF LONDON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'MILE END ROAD',
            'address_line_2' => '',
            'county' => 'LONDON',
            'city' => 'LONDON',
            'postcode' => 'E1 4NS',
        ]);

        Organisation::create([
            'organisation_name' => "QUEEN'S UNIVERSITY BELFAST",
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'RIDDEL HALL',
            'street' => '185 STRANMILLIS ROAD',
            'address_line_2' => '',
            'county' => 'BELFAST',
            'city' => 'BELFAST',
            'postcode' => 'BT9 5EE',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF SHEFFIELD',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'WESTERN BANK',
            'street' => '',
            'address_line_2' => '',
            'county' => 'SHEFFIELD',
            'city' => 'SHEFFIELD',
            'postcode' => 'S10 2TN',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF SOUTHAMPTON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'UNIVERSITY ROAD',
            'address_line_2' => '',
            'county' => 'SOUTHAMPTON',
            'city' => 'SOUTHAMPTON',
            'postcode' => 'SO17 1BJ',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY COLLEGE LONDON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'GOWER STREET',
            'address_line_2' => '',
            'county' => 'LONDON',
            'city' => 'LONDON',
            'postcode' => 'WC1E 6BT',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF WARWICK',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'COVENTRY',
            'city' => 'COVENTRY',
            'postcode' => 'CV4 7AL',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF YORK',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'YORK',
            'city' => 'HESLINGTON',
            'postcode' => 'YO10 5DD',
        ]);

        Organisation::create([
            'organisation_name' => 'OXFORD BROOKES UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'HEADINGTON CAMPUS',
            'address_line_2' => '',
            'county' => 'OXFORD',
            'city' => 'OXFORD',
            'postcode' => 'OX3 0BP',
        ]);

        Organisation::create([
            'organisation_name' => 'DIAMOND LIGHT SOURCE LTD',
            'organisation_type' => 'INDUSTRY',
            'building_name_no' => 'DIAMOND HOUSE',
            'street' => 'HARWELL SCIENCE & INNOVATION CAMPUS',
            'address_line_2' => '',
            'county' => 'OXFORDSHIRE',
            'city' => 'DIDCOT',
            'postcode' => 'OX11 0DE',
        ]);

        Organisation::create([
            'organisation_name' => 'THE FARADAY INSTITUTION',
            'organisation_type' => 'FUNDER',
            'building_name_no' => 'QUAD ONE',
            'street' => 'BECQUEREL AVENUE',
            'address_line_2' => 'HARWELL CAMPUS',
            'county' => 'OXFORDSHIRE',
            'city' => 'DIDCOT',
            'postcode' => 'OX11 0RA',
        ]);

        Organisation::create([
            'organisation_name' => 'ENGINEERING & PHYSICAL SCIENCES RESEARCH COUNCIL',
            'organisation_type' => 'FUNDER',
            'building_name_no' => 'POLARIS HOUSE',
            'street' => 'NORTH STAR AVENUE',
            'address_line_2' => '',
            'county' => 'WILTSHIRE',
            'city' => 'SWINDON',
            'postcode' => 'SN2 1ET',
        ]);

    }
}
