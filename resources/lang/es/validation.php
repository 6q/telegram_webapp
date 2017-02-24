<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => ":attribute ha sido aceptado.",
	"active_url"           => ":attribute no es una dirección valida.",
	"after"                => ":attribute debe ser una fecha después de :date.",
	"alpha"                => ":attribute solo debe contener letras.",
	"alpha_dash"           => ":attribute solo debe contener letras, números y guiones.",
	"alpha_num"            => ":attribute solo debe contener letras y números.",
	"array"                => ":attribute must be an array.",
	"before"               => ":attribute debe ser una fecha antes de :date.",
	"between"              => [
		"numeric" => ":attribute debe ser entre :min y :max.",
		"file"    => ":attribute debe ser entre :min y :max Kb.",
		"string"  => ":attribute debe ser entre :min y :max carácteres.",
		"array"   => ":attribute debe ser entre :min y :max ítems.",
	],
	"boolean"              => ":attribute debe ser entre Si o No.",
	"confirmed"            => ":attribute no se ha podido confirmar.",
	"date"                 => ":attribute no es una fecha válida.",
	"date_format"          => ":attribute no tiene el formato :format.",
	"different"            => ":attribute y :other deben ser diferentes.",
	"digits"               => ":attribute debe tener :digits dígitos.",
	"digits_between"       => ":attribute debe tener entre :min y :max dígitos.",
	"email"                => ":attribute debe ser un E-mail válido.",
	"filled"               => ":attribute campo requerido.",
	"exists"               => ":attribute no es correcto.",
	"image"                => ":attribute debe ser una imagen.",
	"in"                   => ":attribute no es correcto.",
	"integer"              => ":attribute debe estar entero.",
	"ip"                   => ":attribute debe ser una IP válida.",
	"max"                  => [
		"numeric" => ":attribute no puede ser mayor que :max.",
		"file"    => ":attribute no puede ser mayor que :max Kb.",
		"string"  => ":attribute no puede tener más de :max carácteres.",
		"array"   => ":attribute no puede tener más de :max ítems.",
	],
	"mimes"                => ":attribute debe ser un archivo en formato: :values.",
	"min"                  => [
		"numeric" => ":attribute debe ser menor de :min.",
		"file"    => ":attribute debe ser menor de :min Kb.",
		"string"  => ":attribute debe ser menor de :min carácteres.",
		"array"   => ":attribute debe tener menos de :min ítems.",
	],
	"not_in"               => "Este campo es correcto.",
	"numeric"              => "Este campo debe ser un número.",
	"regex"                => "Este campo no tiene un formato válido.",
	"required"             => "Este campo es necesario.",
	"required_if"          => "Este campo es neceario cuando :other es :value.",
	"required_with"        => "Este campo es neceario cuando :values está presente.",
	"required_with_all"    => "Este campo es neceario cuando :values está presente.",
	"required_without"     => "Este campo es neceario cuando :values no está presente.",
	"required_without_all" => "Este campo es neceario cuando :values no está presente.",
	"same"                 => "Este campo y otro coinciden.",
	"size"                 => [
		"numeric" => "Este campo debe tener :size.",
		"file"    => "Este campo debe tener :size Kb.",
		"string"  => "Este campo debe tener :size carácteres.",
		"array"   => "Este campo debe tener :size ítems.",
	],
	"unique"               => "Este campo ha sido aceptado.",
	"url"                  => "Este campo tiene un formato inválido.",
    "tags"                 => "tags, separados por comas(sin espacios) y debe tener un máximo de 50 carácteres.",
	"timezone"             => "Este campo deber ser una zona válida.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name' => 'Nombre',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [
		"log" => "E-mail o Password"
	],

];
