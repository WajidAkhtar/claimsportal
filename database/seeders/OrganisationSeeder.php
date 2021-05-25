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
            'logo' => 'UNIVERSITY OF BIRMINGHAM 72.jpg',
            'logo_high' => 'UNIVERSITY OF BIRMINGHAM 300.jpg',
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
            'logo' => 'UNIVERSITY OF BRISTOL 72.jpg',
            'logo_high' => 'UNIVERSITY OF BRISTOL 300.jpg',
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
            'logo' => 'UNIVERSITY OF CAMBRIDGE 72.jpg',
            'logo_high' => 'UNIVERSITY OF CAMBRIDGE 300.jpg',
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
            'logo' => 'CARDIFF UNIVERSITY 72.jpg',
            'logo_high' => 'CARDIFF UNIVERSITY 300.jpg',
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
            'logo' => 'DURHAM UNIVERSITY 72.jpg',
            'logo_high' => 'DURHAM UNIVERSITY 300.jpg',
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
            'logo' => 'UNIVERSITY OF EDINBURGH 72.jpg',
            'logo_high' => 'UNIVERSITY OF EDINBURGH 300.jpg',
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
            'logo' => 'UNIVERSITY OF EXETER 72.jpg',
            'logo_high' => 'UNIVERSITY OF EXETER 300.jpg',
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
            'logo' => 'UNIVERSITY OF GLASGOW 72.jpg',
            'logo_high' => 'UNIVERSITY OF GLASGOW 300.jpg',
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
            'logo' => 'IMPERIAL COLLEGE LONDON 72.jpg',
            'logo_high' => 'IMPERIAL COLLEGE LONDON 300.jpg',
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
            'logo' => 'UNIVERSITY OF LEEDS 72.jpg',
            'logo_high' => 'UNIVERSITY OF LEEDS 300.jpg',
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
            'logo' => 'UNIVERSITY OF LIVERPOOL 72.jpg',
            'logo_high' => 'UNIVERSITY OF LIVERPOOL 300.jpg',
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
            'logo' => 'LONDON SCHOOL OF ECONOMICS & POLITICAL SCIENCE 72.jpg',
            'logo_high' => 'LONDON SCHOOL OF ECONOMICS & POLITICAL SCIENCE 300.jpg',
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
            'logo' => 'UNIVERSITY OF MANCHESTER 72.jpg',
            'logo_high' => 'UNIVERSITY OF MANCHESTER 300.jpg',
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
            'logo' => 'NEWCASTLE UNIVERSITY 72.jpg',
            'logo_high' => 'NEWCASTLE UNIVERSITY 300.jpg',    
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
            'logo' => 'UNIVERSITY OF NOTTINGHAM 72.jpg',
            'logo_high' => 'UNIVERSITY OF NOTTINGHAM 300.jpg',
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
            'logo' => 'UNIVERSITY OF OXFORD 72.jpg',
            'logo_high' => 'UNIVERSITY OF OXFORD 300.jpg',
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
            'logo' => 'QUEEN MARY UNIVERSITY OF LONDON 72.jpg',
            'logo_high' => 'QUEEN MARY UNIVERSITY OF LONDON 300.jpg',            
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
            'logo' => "QUEEN'S UNIVERSITY BELFAST 72.jpg",
            'logo_high' => "QUEEN'S UNIVERSITY BELFAST 300.jpg",
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
            'logo' => 'UNIVERSITY OF SHEFFIELD 72.jpg',
            'logo_high' => 'UNIVERSITY OF SHEFFIELD 300.jpg',
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
            'logo' => 'UNIVERSITY OF SOUTHAMPTON 72.jpg',
            'logo_high' => 'UNIVERSITY OF SOUTHAMPTON 300.jpg',
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
            'logo' => 'UNIVERSITY COLLEGE LONDON 72.jpg',
            'logo_high' => 'UNIVERSITY COLLEGE LONDON 300.jpg',
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
            'logo' => 'UNIVERSITY OF WARWICK 72.jpg',
            'logo_high' => 'UNIVERSITY OF WARWICK 300.jpg',
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
            'logo' => 'UNIVERSITY OF YORK 72.jpg',
            'logo_high' => 'UNIVERSITY OF YORK 300.jpg',
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
            'logo' => 'OXFORD BROOKES UNIVERSITY 72.jpg',
            'logo_high' => 'OXFORD BROOKES UNIVERSITY 300.jpg',
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
            'logo' => 'DIAMOND LIGHT SOURCE LTD 72.jpg',
            'logo_high' => 'DIAMOND LIGHT SOURCE LTD 300.jpg',
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
            'logo' => 'THE FARADAY INSTITUTION 72.jpg',
            'logo_high' => 'THE FARADAY INSTITUTION 300.jpg',
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
            'logo' => 'ENGINEERING & PHYSICAL SCIENCES RESEARCH COUNCIL 72.jpg',
            'logo_high' => 'ENGINEERING & PHYSICAL SCIENCES RESEARCH COUNCIL 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF LEICESTER',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'UNIVERSITY ROAD',
            'address_line_2' => '',
            'county' => 'LEICESTERSHIRE',
            'city' => 'LEICESTER',
            'postcode' => 'LE1 7RH',
            'logo' => 'UNIVERSITY OF LEICESTER 72.jpg',
            'logo_high' => 'UNIVERSITY OF LEICESTER 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'BRUNEL UNIVERSITY LONDON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'KINGSTON LN',
            'street' => 'UXBRIDGE',
            'address_line_2' => '',
            'county' => 'LONDON',
            'city' => 'LONDON',
            'postcode' => 'UB8 3PH',
            'logo' => 'BRUNEL UNIVERSITY LONDON 72.jpg',
            'logo_high' => 'BRUNEL UNIVERSITY LONDON 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'LANCASTER UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'BAILRIGG',
            'address_line_2' => '',
            'county' => 'LANCASHIRE',
            'city' => 'LANCASTER',
            'postcode' => 'LA1 4YW',
            'logo' => 'LANCASTER UNIVERSITY 72.jpg',
            'logo_high' => 'LANCASTER UNIVERSITY 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'PLYMOUTH UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'DRAKE CIRCUS',
            'address_line_2' => '',
            'county' => 'DEVON',
            'city' => 'PLYMOUTH',
            'postcode' => 'PL4 8AA',
            'logo' => 'PLYMOUTH UNIVERSITY 72.jpg',
            'logo_high' => 'PLYMOUTH UNIVERSITY 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF NORTHAMPTON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'WATERSIDE CAMPUS',
            'street' => 'UNIVERSITY DR',
            'address_line_2' => '',
            'county' => 'NORTHAMPTONSHIRE',
            'city' => 'NORTHAMPTON',
            'postcode' => 'NN1 5PH',
            'logo' => 'UNIVERSITY OF NORTHAMPTON 72.jpg',
            'logo_high' => 'UNIVERSITY OF NORTHAMPTON 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF STRATHCLYDE',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '16 RICHMOND ST',
            'address_line_2' => '',
            'county' => 'LANARKSHIRE',
            'city' => 'GLASGOW',
            'postcode' => 'G1 1XQ',
            'logo' => 'UNIVERSITY OF STRATHCLYDE 72.jpg',
            'logo_high' => 'UNIVERSITY OF STRATHCLYDE 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF ST ANDREWS',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'COLLEGE GATE',
            'address_line_2' => '',
            'county' => 'SCOTLAND',
            'city' => 'FIFE',
            'postcode' => 'KY16 9AJ',
            'logo' => 'UNIVERSITY OF ST ANDREWS 72.jpg',
            'logo_high' => 'UNIVERSITY OF ST ANDREWS 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'LOUGHBOROUGH UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'EPINAL WAY',
            'address_line_2' => '',
            'county' => 'LEICESTERSHIRE',
            'city' => 'LOUGHBOROUGH',
            'postcode' => 'LE11 3TU',
            'logo' => 'LOUGHBOROUGH UNIVERSITY 72.jpg',
            'logo_high' => 'LOUGHBOROUGH UNIVERSITY 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF BATH',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'CLAVERTON DOWN',
            'address_line_2' => '',
            'county' => 'NORTH EAST SOMERSET',
            'city' => 'BATH',
            'postcode' => 'BA2 7AY',
            'logo' => 'UNIVERSITY OF BATH 72.jpg',
            'logo_high' => 'UNIVERSITY OF BATH 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF EAST ANGLIA',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'NORWICH RESEARCH PARK',
            'address_line_2' => '',
            'county' => 'NORFOLK',
            'city' => 'NORWICH',
            'postcode' => 'NR4 7TJ',
            'logo' => 'UNIVERSITY OF EAST ANGLIA 72.jpg',
            'logo_high' => 'UNIVERSITY OF EAST ANGLIA 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF DUNDEE',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'NETHERGATE',
            'address_line_2' => '',
            'county' => 'SCOTLAND',
            'city' => 'DUNDEE',
            'postcode' => 'DD1 4HN',
            'logo' => 'UNIVERSITY OF DUNDEE 72.jpg',
            'logo_high' => 'UNIVERSITY OF DUNDEE 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF ABERDEEN',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => "KING'S COLLEGE",
            'address_line_2' => '',
            'county' => 'ABERDEENSHIRE',
            'city' => 'ABERDEEN',
            'postcode' => 'AB24 3FX',
            'logo' => 'UNIVERSITY OF ABERDEEN 72.jpg',
            'logo_high' => 'UNIVERSITY OF ABERDEEN 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'SWANSEA UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'SINGLETON PARK',
            'address_line_2' => '',
            'county' => 'WALES',
            'city' => 'SWANSEA',
            'postcode' => 'SA2 8PP',
            'logo' => 'SWANSEA UNIVERSITY 72.jpg',
            'logo_high' => 'SWANSEA UNIVERSITY 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF SURREY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'UNIVERSITY CAMPUS',
            'street' => 'STAG HILL',
            'address_line_2' => '',
            'county' => 'SURREY',
            'city' => 'GUILDFORD',
            'postcode' => 'GU2 7XH',
            'logo' => 'UNIVERSITY OF SURREY 72.jpg',
            'logo_high' => 'UNIVERSITY OF SURREY 300.jpg',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF READING',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'WHITEKNIGHTS',
            'address_line_2' => '',
            'county' => 'BERKSHIRE',
            'city' => 'READING',
            'postcode' => 'RG6 6AH',
            'logo' => 'UNIVERSITY OF READING 72.jpg',
            'logo_high' => 'UNIVERSITY OF READING 300.jpg',
        ]);

    }
}
