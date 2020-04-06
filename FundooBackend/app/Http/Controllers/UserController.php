<?php

namespace App\Http\Controllers;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header('Access-Control-Request-Method: POST');

use App\Rabbit\RBMQSender;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SendMail;

class UserController extends Controller
{
      /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $this->validate($request, [
            'fname' => 'required|min:3',
            'lname' => 'required|min:3',
            'email' => 'required|email', //  |unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
        ]);

        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('fundoo')->accessToken;
        //$data = $user('id');
        
        /**
         * RBMQ sender
         */

        // $rabbitmq = new RBMQSender();
        // $toEmail = 'akhilesh.mc4@gmail.com';
        // // $toEmail = $user->email;

        // $subject = "Please verify email for login";
        // $message = "Hi " . $user->fname . " " . $user->lname . ",
        // \nThis is email verification mail from Fundoo Login Register system.
        // \nFor complete reset password process and login into system you have to verify you email by click this link.
        // \n" . url('/') . "/api/verifyMail/" . $token . "
        // \nOnce you click this link your email will be verified and you can login into system.\nThanks.";

        // if ($rabbitmq->sendRabQueue($toEmail, $subject, $message)) {
        //     return response()->json(['success' => $token, 'message' => 'Please Check Mail for Email Verification.'], 200);
        // } else {
        //     return response()->json(['success' => $token, 'message' => 'Error While Sending Mail.'], 400);
        // }
        return response()->json(['token' => $token], 200);
    }

    /**
     * 
     */
    public function verifyMail($token)
    {
        $array = preg_split("/\./", $token);
        $decode = base64_decode($array[1]);
        echo $decode;
        $decode = json_decode($decode, true);
        $user_id = $decode['sub'];

        $user = User::where(['id' => $user_id])->first();
        if (!$user) {
            return response()->json(['message' => "Not a Registered Email"], 200);
        } else if ($user->email_verified_at == null) {
            $user->email_verified_at = now();
            $user->save();
            return response()->json(['message' => "Email is Successfully verified"], 201);
        } else {
            return response()->json(['message' => "Email Already verified"], 202);
        }
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('fundoo')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        } 
    }
    
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }

    /**
     * reset password
     */

    public function forgetPassword(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 201);
        }

        $input = $request->all();
        $user = User::where($input)->first();

        if ($user) {

            $token = $user->createToken('star')->accessToken;
            //     $data = $user->id;

            $rabbitmq = new RBMQSender();
            $toEmail = 'akhilesh.mc4@gmail.com';
            //$toEmail = $user->email;

            $subject = "Please verify email to reset your password";
//            $message = "Hi " . $user->fname . " " . $user->lname . ", \nThis is email verification mail from Fundoo Login";
            $message = "Hi " . $user->fname . " " . $user->lname . ",
            \nThis is email verification mail from Fundoo Login Register system.
            \nFor complete reset password process and login into system you have to verify you email by click this link.
            \n" . url('/') . "/api/resetPassword/" . $token . "
            \nOnce you click this link your email will be verified and you can login into system.
            \nThanks.";

            if ($rabbitmq->sendRabQueue($input['email'], $subject, $message)) {
                return response()->json(['success' => $token, 'message' => 'Please Check Mail for Email Verification.'], 200);
            } else {
                return response()->json(['success' => $token, 'message' => 'Error While Sending Mail.'], 400);
            }
        } else {
            return response()->json(['message' => 'Email id is not Registered'], 400);
        }
    }

    /**
     * reset password
     */

    public function resetPassword(Request $request, $token)
    {
        $validator = $request->validate(
            [
                'password' => 'required|confirmed',
            ]
        );
        $array = preg_split("/\./", $token);
        $decode = base64_decode($array[1]);
        $decode = json_decode($decode, true);
        $id = $decode['sub'];
        $user = User::where(['id' => $id])->first();
        if ($user) {
            $user->password = bcrypt($validator['password']);
            $user->save();
            return response()->json(['message' => 'Password is changed'], 200);
        } else {
            return response()->json(['message' => 'unknown'], 400);
        }
    }

     
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

}
