<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipality;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Municipality::truncate();

        $municipalities = [
            ['name' => 'Popayán', 'department' => 'Cauca', 'description' => 'Capital del departamento del Cauca'],
            ['name' => 'Almaguer', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Argelia', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Balboa', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Bolívar', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Buenos Aires', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Cajibío', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Caldono', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Caloto', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Corinto', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'El Tambo', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Florencia', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Guachené', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Guapi', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Inzá', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Jambaló', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'La Sierra', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'La Vega', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'López de Micay', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Mercaderes', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Miranda', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Morales', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Padilla', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Páez', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Patía', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Piamonte', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Piendamó', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Puerto Tejada', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Puracé', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Rosas', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'San Sebastián', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Santa Rosa', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Santander de Quilichao', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Silvia', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Sotará', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Suárez', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Sucre', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Timbío', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Timbiquí', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Toribío', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Totoró', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
            ['name' => 'Villa Rica', 'department' => 'Cauca', 'description' => 'Municipio del Cauca'],
        ];

        Municipality::insert($municipalities);
    }
}
