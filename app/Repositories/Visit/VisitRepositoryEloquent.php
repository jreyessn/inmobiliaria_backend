<?php

namespace App\Repositories\Visit;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Visit\VisitRepository;
use App\Models\Visit\Visit;
use App\Models\Visit\VisitsCommitment;
use App\Models\Visit\VisitsMortality;
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
        'result' => 'like',
        'farm.centro' => 'like',
        'farm.nombre_centro' => 'like',
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

        $this->saveQuestions($visit, $data['questions']);
        $this->saveMortalities($visit, $data['mortalities']);
        $this->saveCommitments($visit, $data['commitments']);

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

        $this->saveQuestions($visit, $data['questions']);
        $this->saveMortalities($visit, $data['mortalities']);
        $this->saveCommitments($visit, $data['commitments']);

        return $visit;
    }

    /**
     * Guardar las respuestas de las preguntas en la Visita
     * 
     * @param App\Models\Visit\Visit $visit
     * @param array $questions
     * @return void
     */

     public function saveQuestions(Visit $visit, array $questions): void
     {

        foreach($questions as $question){

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

    /**
     * Guardar los registros de morbilidad
     * 
     * @param App\Models\Visit\Visit $visit
     * @param array $mortalities
     * @return void
     */

     public function saveMortalities(Visit $visit, array $mortalities): void
     {

        foreach($mortalities as $mortality){

            VisitsMortality::updateOrCreate(
                [
                    'visit_id' => $visit->id,
                    'building' => $mortality['building']
                ],
                [
                    'visit_id' => $visit->id,
                    'building' => $mortality['building'],
                    'mort_acum' => $mortality['mort_acum'],
                    'mort_current_week' => $mortality['mort_current_week'],
                    'pigs_age' => $mortality['pigs_age'],
                    'pigs_fever' => $mortality['pigs_fever'],
                    'activity' => $mortality['activity'],
                    'cought' => $mortality['cought'],
                    'diarrhea' => $mortality['diarrhea'],
                    'pigs_treated_day' => $mortality['pigs_treated_day'],
                ]
            );
        }
     }

    /**
     * Guardar los compromisos de las preguntas
     * 
     * @param App\Models\Visit\Visit $visit
     * @param array $commtments
     * @return void
     */
     public function saveCommitments(Visit $visit, array $commtments): void
     {

        foreach($commtments as $commitment){

            VisitsCommitment::updateOrCreate(
                [
                    'visit_id' => $visit->id,
                    'question_id' => $commitment['question_id']
                ],
                [
                    'visit_id' => $visit->id,
                    'question_id' => $commitment['question_id'],
                    'title' => $commitment['title'],
                    'description' => $commitment['description'],
                    'date' => $commitment['date'],
                ]
            );
        }
     }
     
}
