<?php

if (! function_exists('companyLogo')) {

    function companyLogo(string $companyName): ?string
    {

        $logos = [

            "Lowe's" => asset('images/company-logos/lowes.webp'),
            'Tata Consultancy Services' => asset('images/company-logos/tcs.jpg'),
            'Infosys' => asset('images/company-logos/infosys.png'),
            'Accenture' => asset('images/company-logos/accenture.png'),
            'Axis Bank' => asset('images/company-logos/axis-bank.jpg'),
            'DHL' => asset('images/company-logos/dhl.jpg'),
            'Amazon' => asset('images/company-logos/amazon.png'),
            'Kotak Mahindra Bank' => asset('images/company-logos/kotak-mahindra-bank.png'),
            'Adani Group' => asset('images/company-logos/adani-group.jpg'),
            'MUFG' => asset('images/company-logos/mufg.jpg'),
            'GE Vernova' => asset('images/company-logos/ge-vernova.png'),
            'Jones Lang LaSalle' => asset('images/company-logos/jll.png'),
            'HCLTech' => asset('images/company-logos/hcltech.jpg'),
            'CIBC India' => asset('images/company-logos/CIBC-Symbol.png'),
            'Mercor' => asset('images/company-logos/mecor-logo.png'),

        ];

        return $logos[$companyName] ?? null;
    }
}