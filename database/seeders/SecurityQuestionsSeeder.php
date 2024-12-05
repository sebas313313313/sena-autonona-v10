<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecurityQuestionsSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            ['question' => '¿Cuál es el nombre de tu primera mascota?'],
            ['question' => '¿En qué ciudad conociste a tu pareja?'],
            ['question' => '¿Cuál es el nombre de tu mejor amigo de la infancia?'],
            ['question' => '¿Cuál fue el nombre de tu primera escuela?'],
            ['question' => '¿Cuál es el segundo nombre de tu madre?'],
            ['question' => '¿Cuál es el nombre de la calle donde creciste?'],
            ['question' => '¿Cuál fue tu primer número de teléfono?'],
            ['question' => '¿Cuál es el nombre de tu profesor favorito?'],
            ['question' => '¿En qué ciudad se conocieron tus padres?'],
            ['question' => '¿Cuál es el nombre de tu primer jefe?'],
        ];

        DB::table('security_questions')->insert($questions);
    }
}
