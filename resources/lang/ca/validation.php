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

	"accepted"             => ":attribute s'ha d'acceptar.",
	"active_url"           => ":attribute no és una adreça vàlida.",
	"after"                => ":attribute ha de ser una data després del :date.",
	"alpha"                => ":attribute només pot contenir lletres.",
	"alpha_dash"           => ":attribute només pot contenir lletres, números i guions.",
	"alpha_num"            => ":attribute només pot contenir lletres i números.",
	"array"                => ":attribute ha de ser un array.",
	"before"               => ":attribute ha de ser una data abans del :date.",
	"between"              => [
		"numeric" => ":attribute ha de ser entre :min i :max.",
		"file"    => ":attribute ha de ser entre :min i :max Kb.",
		"string"  => ":attribute ha de ser entre :min i :max caràcters.",
		"array"   => ":attribute ha de ser entre :min i :max ítems.",
	],
	"boolean"              => ":attribute camp ha de ser Si o No",
	"confirmed"            => ":attribute confirmació no és correcta.",
	"date"                 => ":attribute no és una data vàlida.",
	"date_format"          => ":attribute el format no és correcte :format.",
	"different"            => ":attribute i :other han de ser diferents.",
	"digits"               => ":attribute han de ser :digits dígits.",
	"digits_between"       => ":attribute han de ser entre :min i :max dígits.",
	"email"                => ":attribute ha de ser un E-mail correcte.",
	"filled"               => ":attribute camp es requereix.",
	"exists"               => ":attribute es correcte.",
	"image"                => ":attribute ha de ser una imatge.",
	"in"                   => ":attribute no es correcte.",
	"integer"              => ":attribute ha de ser complet.",
	"ip"                   => ":attribute ha de ser una IP vàlida.",
	"max"                  => [
		"numeric" => ":attribute no pot ser més gran que :max.",
		"file"    => ":attribute no pot ser més gran que :max Kb.",
		"string"  => ":attribute no pot ser més gran que :max caràcters.",
		"array"   => ":attribute no pot tenir més de :max ítems.",
	],
	"mimes"                => ":attribute ha de ser un arxiu format: :values.",
	"min"                  => [
		"numeric" => ":attribute ha de ser com a mínim de :min.",
		"file"    => ":attribute ha de ser com a mínim de :min Kb.",
		"string"  => ":attribute ha de tenir mínim :min caràcters.",
		"array"   => ":attribute ha de tenir mínim :min ítems.",
	],
	"not_in"               => ":attribute és correcte.",
	"numeric"              => ":attribute ha de ser un número.",
	"regex"                => ":attribute format no és correcte.",
	"required"             => ":attribute camp és requerit.",
	"required_if"          => ":attribute camp és requerit quan :other és :value.",
	"required_with"        => ":attribute camp és requerit quan :values és present.",
	"required_with_all"    => ":attribute camp és requerit quan :values és present.",
	"required_without"     => ":attribute camp és requerit quan :values no és present.",
	"required_without_all" => ":attribute camp és requerit quan no :values és present.",
	"same"                 => ":attribute i :other han de coincidir.",
	"size"                 => [
		"numeric" => ":attribute ha de tenir :size.",
		"file"    => ":attribute ha de tenir :size Kb.",
		"string"  => ":attribute ha de ternir :size caràcters.",
		"array"   => ":attribute ha de tenir :size ítems.",
	],
	"unique"               => ":attribute ja està agafat.",
	"url"                  => ":attribute format no és correcte.",
    "tags"                 => "tags separat per comes (sense espais) i un màxim de 50 caràcters.",
	"timezone"             => ":attribute ha de ser una zona vàlida.",

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
			'rule-name' => 'Missatge',
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
