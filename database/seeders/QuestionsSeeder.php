<?php

namespace Database\Seeders;

use App\Models\Question\Question;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Question::insert([
            [
                'description' => 'Nivel de Lagunas (Sist. en Norma)',
                'sections_question_id' => 1,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 1
            ],
            [
                'description' => 'Cárcamo - (Nivel/Canastilla/Bomba/Bote residuos, Limp., Sist. Eléct., Cerco)',
                'sections_question_id' => 1,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 2
            ],
            [
                'description' => 'Canaletas OK',
                'sections_question_id' => 1,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 3
            ],
            [
                'description' => 'Formato Ambiental OK',
                'sections_question_id' => 1,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 4
            ],
            [
                'description' => 'Jaula',
                'sections_question_id' => 2,
                'max_score' => 0.5,
                'score_fractional' => 0.5,
                'order' => 5
            ],
         
            [
                'description' => 'Cal',
                'sections_question_id' => 2,
                'max_score' => 0.5,
                'score_fractional' => 0.5,
                'order' => 6
            ],
         
            [
                'description' => 'Paja',
                'sections_question_id' => 2,
                'max_score' => 0.5,
                'score_fractional' => 0.5,
                'order' => 7
            ],
         
            [
                'description' => 'Limpieza',
                'sections_question_id' => 2,
                'max_score' => 0.5,
                'score_fractional' => 0.5,
                'order' => 8
            ],
         
            [
                'description' => 'Protocolo',
                'sections_question_id' => 2,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 9
            ],
         
            [
                'description' => 'Flujo',
                'sections_question_id' => 3,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 10
            ],
         
            [
                'description' => 'Fugas',
                'sections_question_id' => 3,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 11
            ],
         
            [
                'description' => 'Altura Chupones',
                'sections_question_id' => 3,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 12
            ],
         
            [
                'description' => 'Nivel de Cloro',
                'sections_question_id' => 3,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 13
            ],
         
            [
                'description' => 'Dieta Correcta de Acuerdo a Edad',
                'sections_question_id' => 4,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 14
            ],
         
            [
                'description' => 'Correcta Regulación de Comedero OK',
                'sections_question_id' => 4,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 15
            ],
         
            [
                'description' => 'Sistemas de Alimento OK',
                'sections_question_id' => 4,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 16
            ],
         
            [
                'description' => 'No Desperdicios Evidente de Alimento',
                'sections_question_id' => 4,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 17
            ],
         
            [
                'description' => 'Cuenta con Control de Vuelta',
                'sections_question_id' => 4,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 18
            ],
         
            [
                'description' => 'Se Cuenta con Alimento en Tapete',
                'sections_question_id' => 4,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 19
            ],
            [
                'description' => 'Esta brindando alimento húmedo a población necesaria',
                'sections_question_id' => 4,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 20
            ],
            [
                'description' => 'Temperatura correcta de acuerdo a la edad del cerdo',
                'sections_question_id' => 5,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 21
            ],
         
            [
                'description' => 'La Ventilación en el edificio esta controlada',
                'sections_question_id' => 5,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 22
            ],
         
            [
                'description' => 'El nivel de agua en Charca/Pileta/Paja en Cama es correcta',
                'sections_question_id' => 5,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 23
            ],
            [
                'description' => 'Manejo Correcto de Cerdos A, B y C',
                'sections_question_id' => 6,
                'max_score' => 3,
                'score_fractional' => 1,
                'order' => 24
            ],
            [
                'description' => 'Eutanasia Adecuada',
                'sections_question_id' => 6,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 25
            ],
            [
                'description' => 'Animales Tratados de Acuerdo al Protocolo',
                'sections_question_id' => 6,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 26
            ],
            [
                'description' => 'Corrales (Herrería en Buen Estado), Espacio Correctamente Utilizados',
                'sections_question_id' => 6,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 27
            ],
            [
                'description' => 'Manejo Correcto de Hospital y Recuperación',
                'sections_question_id' => 6,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 28
            ],
            [
                'description' => 'Maneja Corral para Herniados, Criptos y Heridos',
                'sections_question_id' => 6,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 29
            ],
            [
                'description' => 'Personal de Granja (plantilla) Completo',
                'sections_question_id' => 6,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 30
            ],
            [
                'description' => 'Personal se Encuentra Capacitado, hay carpeta con listas de capacitación',
                'sections_question_id' => 6,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 31
            ],
            [
                'description' => 'Sensores de Temperatura OK',
                'sections_question_id' => 7,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 32
            ],
            [
                'description' => 'Techo, Piso, Cortinas, Fijos, Esquineros Funcionan',
                'sections_question_id' => 7,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 33
            ],
            [
                'description' => 'Abanicos Funcionando, Cantidad, Distribución. OK',
                'sections_question_id' => 7,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 34
            ],
            [
                'description' => 'Cuenta con Medicadores',
                'sections_question_id' => 7,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 35
            ],
            [
                'description' => 'Plantas de Emergencia / Motobomba OK',
                'sections_question_id' => 7,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 36
            ],
            [
                'description' => 'Sistemas de Fogueo OK',
                'sections_question_id' => 7,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 37
            ],
            [
                'description' => 'Calentones Funcionando Ok',
                'sections_question_id' => 7,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 38
            ],
            [
                'description' => 'Arco-vado, Cubo UV, Baños, Chutes',
                'sections_question_id' => 7,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 39
            ],
            [
                'description' => 'Registros en el Edificio',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 40
            ],
            
            [
                'description' => 'Registro / Inventario (Animales, Alimento, Medicinas, Equipos, Insumos)',
                'sections_question_id' => 8,
                'max_score' => 2,
                'score_fractional' => 1,
                'order' => 41
            ],
            [
                'description' => 'Registro de Mortalidad',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 42
            ],
            [
                'description' => 'Certificado de Vacunas y Coherencia',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 43
            ],
            [
                'description' => 'Consumo de Alimento',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 44
            ],
            [
                'description' => 'Presupuesto de Alimento',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 45
            ],
            [
                'description' => 'Registro de Tratamientos',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 46
            ],
            [
                'description' => 'Registro Temperaturas Max / Min (cuenta con termómetros funcionales)',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 47
            ],
            [
                'description' => 'Control de Agujas',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 48
            ],
            [
                'description' => 'Formato de Medicación al Agua',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 49
            ],
            [
                'description' => 'Control de Plagas',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 50
            ],
            [
                'description' => 'Pizarras Actualizadas',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 51
            ],
            [
                'description' => 'Registro de Temperatura de Refrigerador de Vacunas',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 52
            ],
            [
                'description' => 'Programa de Medicación / Vacunación',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 53
            ],
            [
                'description' => 'Carpeta Ambiental',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 54
            ],
            [
                'description' => 'Libro Rojo / Extintores / Señales',
                'sections_question_id' => 8,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 55
            ],
            [
                'description' => 'Regaderas (Areas Sucia-Limpia) y Ropa Limpia',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 56
            ],
            [
                'description' => 'Edificios Puertas Cerradas, Malla Pajarera en Buen Estado',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 57
            ],
            [
                'description' => 'Control de Roedores, Cebaderos, Cebo',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 58
            ],
            [
                'description' => 'Cerco Perimetral',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 59
            ],
            [
                'description' => 'Ollas (Tandem si corresponde, Capacidad suficiente) Base Libre de Alimento',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 60
            ],
            [
                'description' => 'Pasillos Limpios y Encalados',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 61
            ],
            [
                'description' => 'No Aves en Edificios',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 62
            ],
            [
                'description' => 'Base de Ollas Encaladas',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 63
            ],
            [
                'description' => 'No Presencia de Vectores (Gatos/Perros/Etc)',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 64
            ],
            [
                'description' => 'Chutes Limpios y Encalados',
                'sections_question_id' => 9,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 65
            ],
            [
                'description' => 'Sitio Libre de Escombro',
                'sections_question_id' => 10,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 66
            ],
            [
                'description' => 'Caminos Funcionales',
                'sections_question_id' => 10,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 67
            ],
            [
                'description' => 'Control de Maleza',
                'sections_question_id' => 10,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 68
            ],
            [
                'description' => 'Disposición de Basura',
                'sections_question_id' => 10,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 69
            ],
            [
                'description' => 'Ofiina-Comedor (cuenta con comedor) Limpieza y Orden',
                'sections_question_id' => 10,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 70
            ],
            [
                'description' => 'Herramientas Limpias y Ordenadas',
                'sections_question_id' => 10,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 71
            ],
            [
                'description' => 'Almacén Limpio y Tarjetón de Materiales',
                'sections_question_id' => 10,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 72
            ],
            [
                'description' => 'Equipos de Granja Limpios y Funcionales',
                'sections_question_id' => 10,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 73
            ],
            [
                'description' => 'Protocolo de Movimiento de Animales',
                'sections_question_id' => 11,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 74
            ],
            [
                'description' => 'Aplica Correctamente Protocolo de Aguja Rota',
                'sections_question_id' => 11,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 75
            ],
            [
                'description' => 'Maneja Protocolo de Tiempo de Retiro de Antibióticos',
                'sections_question_id' => 11,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 76
            ],
            [
                'description' => 'Seguimiento a Planes de Acción Auditorias',
                'sections_question_id' => 11,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 77
            ],
            [
                'description' => 'Aplica Protocolo para Evitar Contaminación Físico, Químico y Biológico',
                'sections_question_id' => 11,
                'max_score' => 1,
                'score_fractional' => 0.5,
                'order' => 78
            ],
        ]);
    }
}
