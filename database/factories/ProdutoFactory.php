<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Produto;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Faker\Provider\pt_BR\Person;

$factory->define(Produto::class, function (Faker $faker) {
	return [
		'descricao' => $faker->word,
		'valorvenda' => "30,00",
		'minimo' => "15,00",
		'unidade_id' => 1,
	];
});
