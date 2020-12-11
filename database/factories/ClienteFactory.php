<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cliente;
// use Faker\Generator as Faker;
// use Illuminate\Support\Str;
// use Faker\Provider\pt_BR\Person;

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Cliente::class, function (Faker $faker) {
	return [
		'nomerazao' => $faker->name,
		'fantasia' => $faker->word,
		'cpfcnpj' => $faker->unique()->cnpj,
		'rgie' => $faker->rg,
		'email' => $faker->unique()->email,
		'celular' => $faker->cellphoneNumber,
		'telefone' => $faker->landlineNumber,
		'tipo' => 'Jurídica',
	];
});
