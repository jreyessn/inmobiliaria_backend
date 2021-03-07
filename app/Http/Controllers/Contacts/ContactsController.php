<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contacts\ContactUpdateRequest;
use App\Repositories\Contact\ContactRepositoryEloquent;
use App\Repositories\Users\UserRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    private $contactRepository;
    private $userRepository;

    function __construct(
        ContactRepositoryEloquent $contactRepository,
        UserRepositoryEloquent $userRepository
    )
    {
        $this->contactRepository = $contactRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
        ]);

        $perPage = $request->get('perPage', config('repository.pagination.limit'));

        return $this->contactRepository->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|nullable|email|unique:users,email|unique:contacts,email',
            'phone' => 'nullable',
            'customer_id' => "nullable|exists:customers,id",
            'address' => 'nullable',
            'language' => 'nullable',
            'note' => 'nullable',
            'avatar' => 'nullable',
            'password' => 'nullable|string|min:6|required_with:password_confirm|same:password_confirm',
            'password_confirm' => 'nullable|string|min:6',
        ]);

        DB::beginTransaction();

        try{
            
            $data = $request->all();

            /**
             * Al colocar una contraseña, se asigna un usuario para acceder al portal de clientes
             */
            if($request->password){
                $user = $this->userRepository->create([
                    'email' => $request->email,
                    'name' => $request->name,
                    'username' => preg_replace('/@.*?$/', '', $request->email),
                    'password' => $request->password,
                ]);
                $user->roles()->sync([ 3 ]);

                $data['user_id'] = $user->id;
            }

            $data = $this->contactRepository->save($data);
            
            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
                "data" => $data
            ], 201);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->contactRepository->find($id)->load('user', 'customer');
        
        return compact('data');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, ContactUpdateRequest $request)
    {
        $id = $request->route('contact') ?? $request->user()->contact->id ?? null;

        $userId = $this->contactRepository->find($id)->user_id ?? null;

        DB::beginTransaction();

        try{
            $data = $request->all();
            /**
             * No se ha creado el usuario y han actualizado la contraseña. Se crea usuario
             */
            if($request->password && is_null($userId)){
                $user = $this->userRepository->create([
                    'email' => $request->email,
                    'name' => $request->name,
                    'username' => preg_replace('/@.*?$/', '', $request->email),
                    'password' => $request->password,
                ]);

                $data['user_id'] = $user->id;
            }
            
            /**
             * Contacto tiene usuario asignado. Se actualiza valores
             */
            if($userId){
                $user = $this->userRepository->find($userId);
                $user->email = $request->email;
                $user->name  = $request->name;
                $user->username  = preg_replace('/@.*?$/', '', $request->email);

                if($request->password)
                    $user->password = $request->password;

                $user->save();
            }

            $this->contactRepository->saveUpdate($data, $id);
            
            DB::commit();

            return response()->json([
                "message" => "Actualización éxitosa",
            ], 201);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $contact = $this->contactRepository->find($id);
            $contact->delete();

            if($contact->user){
                $contact->user->delete();
            }

            return response()->json(null, 204);

        }catch(\Exception $e){

            return response()->json(null, 404);

        }
    }
}
