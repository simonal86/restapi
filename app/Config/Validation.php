<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    public $usuario = [
        'nombre' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'El campo es obligatorio.',
            ],
        ],
        'email' => [
            'rules'  => 'required|valid_email',
            'errors' => [
                'required' => 'El campo es obligatorio.',
                'valid_email' => 'Debe ser un email valido.',
            ],
        ],
        'genero' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'El campo es obligatorio.',
            ],
        ],
        'activo' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'El campo es obligatorio.',
            ],
        ]
    ];
    
    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
}
