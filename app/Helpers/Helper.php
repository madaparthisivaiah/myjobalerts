<?php

if (! function_exists('companyLogo')) {

    function companyLogo(string $companyName): ?string
    {
        $logos = [

            "Lowe's" => asset('images/company-logos/lowes.webp'),
            'TCS' => asset('images/company-logos/tcs.png'),
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

        ];

        return $logos[$companyName] ?? null;
    }
}