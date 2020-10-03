<?php

namespace App\Repositories\PasswordReset;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PasswordReset\PasswordResetRepository;
use App\Models\PasswordReset;
use App\Validators\PasswordReset\PasswordResetValidator;

/**
 * Class PasswordResetRepositoryEloquent.
 *
 * @package namespace App\Repositories\PasswordReset;
 */
class PasswordResetRepositoryEloquent extends BaseRepository implements PasswordResetRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PasswordReset::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
