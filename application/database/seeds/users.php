<?php
 
return [
 
    [
        'username' => 'firstuser',
        'password' => Hash::make('first_password'),
        'created_at' => new DateTime,
        'updated_at' => new DateTime
    ],
    [
        'username' => 'seconduser',
        'password' => Hash::make('second_password'),
        'created_at' => new DateTime,
        'updated_at' => new DateTime
    ]
 
];