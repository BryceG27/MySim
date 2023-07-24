<?php

namespace App\Actions\Fortify;

use App\Http\Controllers\SimController;
use App\Models\Sim;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User {
        
        Validator::make($input, [
            'Name' => ['required', 'string', 'max:50'],
            'Surname' => ['required', 'string', 'max:50'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'Phone' => ['max:30'],
            'Mobile' => ['required', 'max:30'],
            'Address' => ['max:200'],
            'City' => ['max:100'],
            'County' => ['max:10'],
            'Country' => ['max:50'],
            /* 'Lang' => ['required','max:2'], */
            'CompanyName' => ['max:100'],
            'CompanyPhone' => ['max:30'],
            'CompanyAddress' => ['max:200'],
            'CompanyCity' => ['max:100'],
            'CompanyCounty' => ['max:10'],
            'CompanyCountry' => ['max:50'],
            'CompanyVAT' => ['max:11'],
            'password' => $this->passwordRules(),
        ])->validate();

        $createUser = User::create([
            'Name' => $input['Name'],
            'Surname' => $input['Surname'],
            'email' => $input['email'],
            'Mobile' => $input['Mobile'],
            'Phone' => $input['Phone'],
            'Address' => $input['Address'],
            'City' => $input['City'],
            'County' => $input['County'],
            'Country' => $input['Country'],
            /* 'Lang' => $input['Lang'], */
            'Lang' => App::getLocale(),
            'CompanyName' => $input['CompanyName'] ?? '',
            'CompanyPhone' => $input['CompanyPhone'] ?? '',
            'CompanyAddress' => $input['CompanyAddress'] ?? '',
            'CompanyCity' => $input['CompanyCity'] ?? '',
            'CompanyCounty' => $input['CompanyCounty'] ?? '',
            'CompanyCountry' => $input['CompanyCountry'] ?? '',
            'CompanyVAT' => $input['CompanyVAT'] ?? '',
            'password' => Hash::make($input['password']),
        ]);

        if(!empty($input['iccid'] && !empty($input['msisdn'] && $createUser))) {
            $sim = Sim::where('iccid', $input['iccid'])->where('msisdn', $input['msisdn'])->first();

            $SimController = new SimController();
            $SimController->associateSim($createUser->id, $sim->id);
        }

        return $createUser;
    }
}
