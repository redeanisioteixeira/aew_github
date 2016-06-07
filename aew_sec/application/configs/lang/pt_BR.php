<?php
return array(
	/* Validators */
	Zend_Validate_NotEmpty::IS_EMPTY              => 'Campo obrigatório',
	Zend_Validate_Regex::NOT_MATCH                => 'Valor inválido',
	Zend_Captcha_Word::BAD_CAPTCHA                => 'Valor inválido',
	Zend_Validate_Date::NOT_YYYY_MM_DD            => 'Formato inválido (dd/mm/aaaa)',
	Zend_Validate_Date::INVALID                   => "Valor de data inválido",
	Zend_Validate_Date::INVALID_DATE              => "'%value%' não parece ser uma data válida",
	Zend_Validate_Date::FALSEFORMAT               => "'%value%' não está no formato de data correto (dd/mm/aaaa)",
	Zend_Validate_StringLength::TOO_SHORT         => 'Campo não pode ter menos do que %min% caracteres',
	Zend_Validate_StringLength::TOO_LONG          => 'Campo não pode ter mais do que %max% caracteres',
	Zend_Validate_EmailAddress::INVALID           => 'Endereço de e-mail inválido',
	Zend_Validate_Digits::NOT_DIGITS              => 'Campo só pode conter algarismos',
	Zend_Validate_File_Upload::NO_FILE            => "O arquivo '%value%' não foi enviado",
	Zend_Validate_File_Upload::INI_SIZE           => "O arquivo '%value%' ultrapassa o tamanho permitido",
	Zend_Validate_File_Upload::FORM_SIZE          => "O arquivo '%value%' ultrapassa o tamanho permitido",
	Zend_Validate_File_Upload::UNKNOWN            => "Um erro desconhecido ocorreu enquanto o arquivo '%value%' era enviado",
	Zend_Validate_File_Extension::FALSE_EXTENSION => "O arquivo '%value%' não possui um formato válido",
	Zend_Validate_File_Size::TOO_BIG              => "Tamanho máximo permitido é de '%max%' mas '%size%' foi detectado"
);
