<?php

namespace App\Repositories\Visit;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Visit\VisitRepository;
use App\Models\Visit\Visit;
use App\Models\Visit\VisitsQuestion;
use App\Validators\Visit\VisitValidator;

/**
 * Class VisitRepositoryEloquent.
 *
 * @package namespace App\Repositories\Visit;
 */
class VisitRepositoryEloquent extends BaseRepository implements VisitRepository
{
    protected $fieldSearchable = [
        'cost_center',
        'result',
        'farm.name',
        'farm.farm_manager.name',
        'farm.sharecropper.name',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Visit::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Guardar visitas
     * 
     * @param array $data
     * @return App\Models\Visit\Visit
     */

    public function save(array $data): Visit
    {
        $data['user_id'] = request()->user()->id ?? 1;
        $visit = $this->create($data);

        $this->saveQuestions($visit, $data);

        return $visit;
    }

    /**
     * Actualizar visitas
     * 
     * @param array $data
     * @param int $id
     * @return App\Models\Visit\Visit
     */

    public function saveUpdate(array $data, int $id): Visit
    {
        $visit = $this->find($id);
        $visit->fill($data);
        $visit->save();

        $this->saveQuestions($visit, $data);

        return $visit;
    }

    /**
     * Guardar las respuestas de las preguntas en la Visita
     * 
     * @param App\Models\Visit\Visit $visit
     * @param array $data
     * @return void
     */

     public function saveQuestions(Visit $visit, array $data): void
     {

        foreach($data['questions'] as $question){

            VisitsQuestion::updateOrCreate(
                [
                    'visit_id' => $visit->id,
                    'question_id' => $question['question_id']
                ],
                [
                    'visit_id' => $visit->id,
                    'question_id' => $question['question_id'],
                    'score' => $question['score']
                ]
            );
        }
     }
     
}
