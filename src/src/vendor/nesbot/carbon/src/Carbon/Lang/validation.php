<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'email'    => 'El :attribute debe ser una dirección de correo válida.',
    'unique'   => 'Este :attribute ya está registrado en nuestra base de datos.',
    'min'      => [
        'string' => 'La :attribute debe tener al menos :min caracteres.',
    ],
    'confirmed' => 'La confirmación de la contraseña no coincide.',

    'attributes' => [
        'nombre'   => 'nombre',
        'apellido' => 'apellido',
        'email'    => 'correo electrónico',
        'password' => 'contraseña',
    ],
];