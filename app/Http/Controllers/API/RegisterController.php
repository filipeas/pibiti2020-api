<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends BaseController
{
    /**
     * Register user of type specialist api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'cpf' => 'required|unique:users,cpf',
            'professional_record' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = new User();
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->cpf = $request->cpf;
        $user->professional_record = $request->professional_record;
        $user->email = $request->email;
        $user->password = bcrypt($request['password']);
        $user->is_specialist = true;
        $user->is_patient = false;
        $user->save();
        // $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        // $input['is_specialist'] = true;
        // $input['is_patient'] = false;
        // $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User of type specialist registered successfully.');
    }

    /**
     * Register user of type patient api
     *
     * @return \Illuminate\Http\Response
     */
    public function registerPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'cpf' => 'required|unique:users,cpf',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = new User();
        $user->pacient = auth()->user()->id;
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->cpf = $request->cpf;
        $user->email = $request->email;
        $user->password = bcrypt($request['password']);
        $user->is_specialist = false;
        $user->is_patient = true;
        $user->save();
        // $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        // $input['is_specialist'] = false;
        // $input['is_patient'] = true;
        // $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User of type patient registered successfully.');
    }

    public function listAllPatients()
    {
        $patients = User::where('is_patient', true)
            ->where('pacient', auth()->user()->id)
            ->get();

        if (is_null($patients)) {
            return $this->sendError('Patient not found.');
        }

        return $this->sendResponse($patients, 'Patients retrieved successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
}
