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
	"active_url"           => ":attribute no es una direcci�n valida.",
	"after"                => ":attribute debe ser una fecha despu�s de :date.",
	"alpha"                => ":attribute solo debe contener letras.",
	"alpha_dash"           => ":attribute solo debe contener letras, n�meros y guiones.",
	"alpha_num"            => ":attribute solo debe contener letras y n�meros.",
	"array"                => ":attribute must be an array.",
	"before"               => ":attribute debe ser una fecha antes de :date.",
	"between"              => [
		"numeric" => ":attribute debe ser entre :min y :max.",
		"file"    => ":attribute debe ser entre :min y :max Kb.",
		"string"  => ":attribute debe ser entre :min y :max car�cteres.",
		"array"   => ":attribute debe ser entre :min y :max �tems.",
	],
	"boolean"              => ":attribute debe ser entre Si o No.",
	"confirmed"            => ":attribute no se ha podido confirmar.",
	"date"                 => ":attribute no es una fecha v�lida.",
	"date_format"          => ":attribute no tiene el formato :format.",
	"different"            => ":attribute y :other deben ser diferentes.",
	"digits"               => ":attribute debe tener :digits d�gitos.",
	"digits_between"       => ":attribute debe tener entre :min y :max d�gitos.",
	"email"                => ":attribute debe ser un E-mail v�lido.",
	"filled"               => ":attribute campo requerido.",
	"exists"               => ":attribute no es correcto.",
	"image"                => ":attribute debe ser una imagen.",
	"in"                   => ":attribute no es correcto.",
	"integer"              => ":attribute debe estar entero.",
	"ip"                   => ":attribute debe ser una IP v�lida.",
	"max"                  => [
		"numeric" => ":attribute no puede ser mayor que :max.",
		"file"    => ":attribute no puede ser mayor que :max Kb.",
		"string"  => ":attribute no puede tener m�s de :max car�cteres.",
		"array"   => ":attribute no puede tener m�s de :max �tems.",
	],
	"mimes"                => ":attribute debe ser un archivo en formato: :values.",
	"min"                  => [
		"numeric" => ":attribute debe ser menor de :min.",
		"file"    => ":attribute debe ser menor de :min Kb.",
		"string"  => ":attribute debe ser menor de :min car�cteres.",
		"array"   => ":attribute debe tener menos de :min �tems.",
	],
	"not_in"               => ":attribute es correcto.",
	"numeric"              => ":attribute debe ser un n�mero.",
	"regex"                => ":attribute el formato no es valido.",
	"required"             => ":attribute es un campo necesario.",
	"required_if"          => ":attribute es un campo neceario cuando :other es :value.",
	"required_with"        => ":attribute es un campo neceario cuando :values est� presente.",
	"required_with_all"    => ":attribute es un campo neceario cuando :values est� presente.",
	"required_without"     => ":attribute es un campo neceario cuando :values no est� presente.",
	"required_without_all" => ":attribute field es un campo neceario cuando :values no est� presente.",
	"same"                 => ":attribute y :other coinciden.",
	"size"                 => [
		"numeric" => ":attribute debe tener :size.",
		"file"    => ":attribute debe tener :size Kb.",
		"string"  => ":attribute debe tener :size car�cteres.",
		"array"   => ":attribute debe tener :size �tems.",
	],
	"unique"               => ":attribute ha sido aceptado.",
	"url"                  => ":attribute es un formato inv�lido.",
    "tags"                 => "tags, separados por comas(sin espacios) y debe tener un m�ximo de 50 car�cteres.",
	"timezone"             => ":attribute deber ser una zona v�lida.",

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
