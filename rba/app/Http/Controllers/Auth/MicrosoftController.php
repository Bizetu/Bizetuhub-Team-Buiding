<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use Exception;
use App;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Edx\EdxAuthUser;
use App\Edx\AuthUserprofile;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use App\Models\TwillPosition;
use App\Models\Domain;
use App\Edx\AccessToken;
use App\Models\MicrositeAccessToken;
use Illuminate\Support\Facades\Log;

class MicrosoftController extends Controller
{
    //
    use AuthenticatesUsers;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
        $this->repository = $this->getRepository();
        $this->edxRepository = $this->getEdxRepository();
    }

    public function redirectToProvider()
    {
        return Socialite::driver('microsoft')
            ->redirect();
    }

    public function handleProviderCallback()

    {

        return  App::environment(['local', 'staging']) ? $this->handleProviderLocal() : $this->handleProviderLive();
    }

    public function handleProviderLocal()
    {
        try {

            $user = Socialite::with('microsoft')->stateless()->user();


            $domain = explode("@", $user->email)[1];

            // Check if domain in allowed
            $domainCheck = Domain::where('name', $domain)->first();


            if ($domainCheck) {

                //check if the user exists on the platform
                $finduser = User::where('microsoft_id', $user->id)->first();



                if ($finduser) {

                    if (empty($finduser->first_name)) {

                        $finduser->first_name = $user->user['givenName'];
                        $finduser->last_name = $user->user['surname'];

                        $finduser->save();
                    }

                    Auth::login($finduser, true);



                    if ($finduser->role_id == 1 || $finduser->role_id == 2 || $finduser->role_id == 3) {

                        $this->authManager->guard('twill_users')->loginUsingId($finduser->id);


                        $finduser->last_login_at = Carbon::now();
                        $finduser->save();

                        return redirect()->route('pages', ['key' => 'courses']);
                    } else {


                        $finduser->last_login_at = Carbon::now();
                        $finduser->save();


                        return redirect()->route('pages', ['key' => 'courses']);
                    }
                } else {
                    //register the User


                    $newUser = $this->repository->createForRegisterMicrosoft($user, $domainCheck->company_id);


                    if (!$newUser) {
                        $course_errors = [
                            'title' => 'Sorry',
                            'content' => 'An error occured while submitting your registration. Please refresh the page and try again.',
                        ];
                        Session::flash('course_errors', $course_errors);
                        return redirect()->to('/login');
                    } else {

                        // Generate a random to be sent to edx as a token.
                        $randomString =  $this->generateRandomString(9);



                        $position = new TwillPosition();
                        $position->user_id = $newUser->id;
                        $position->position = $randomString;

                        $position->save();

                        Auth::login($newUser, true);

                        $newUser->last_login_at = Carbon::now();
                        $newUser->save();

                        return redirect()->route('courses');
                    }
                }
            } else {
                $course_errors = [
                    'title' => 'Sorry',
                    'content' => 'The email or domain is not allowed for this platform',
                ];

                Session::flash('site_errors', $course_errors);
                return redirect()->to('/login');
            }
        } catch (Exception $e) {

            return redirect('/login');
        }
    }



    public function handleProviderLive()
    {
        try {

            $user = Socialite::with('microsoft')->stateless()->user();
            // Log::info('user object after microsoft login',[$user]);


            $domain = explode("@", $user->email)[1];

            // Check if domain in allowed
            $domainCheck = Domain::where('name', $domain)->first();

          
            if ($domainCheck) {

                //check if the user exists on the platform
                $finduser = User::where('microsoft_id', $user->id)->first();

                  


                if ($finduser) {

                    if (empty($finduser->first_name)) {

                        $finduser->first_name = $user['given_name'];
                        $finduser->last_name = $user['family_name'];

                        $finduser->save();
                    }

                    Auth::login($finduser, true);
                    $pd =  TwillPosition::where('user_id', $finduser->id)->first();

                    if ($finduser->role_id == 1 || $finduser->role_id == 2 || $finduser->role_id == 3) {


                        $this->authManager->guard('twill_users')->loginUsingId($finduser->id);


                        $edxStatus = $this->edxRepository->edxLogin($finduser, $pd->position);




                        if (!$edxStatus) {
                            Auth::logout();

                            $course_errors = [
                                'title' => 'Sorry',
                                'content' => 'The username or password you entered did not match our records. Please confirm you have registered',
                            ];

                            Session::flash('site_errors', $course_errors);

                            return redirect()->to('/login');
                        } else {

                            $this->saveAccessToken($finduser, $user);

                            $finduser->last_login_at = Carbon::now();
                            $finduser->save();

                            return redirect()->route('pages', ['key' => 'courses']);
                        }
                    } else {

                        $edxStatus = $this->edxRepository->edxLogin($finduser, $pd->position);



                        if (!$edxStatus) {
                            Auth::logout();

                            $course_errors = [
                                'title' => 'Sorry',
                                'content' => 'The username or password you entered did not match our records. Please confirm you have registered',
                            ];

                            Session::flash('site_errors', $course_errors);

                            return redirect()->to('/login');
                        } else {

                            $this->saveAccessToken($finduser, $user);

                            $finduser->last_login_at = Carbon::now();
                            $finduser->save();

                            return redirect()->route('pages', ['key' => 'courses']);
                        }
                    }
                } else {
                    $newUser = $this->repository->createForRegisterMicrosoft($user, $domainCheck->company_id);

                   

                    if (!$newUser) {

                        $course_errors = [
                            'title' => 'Sorry',
                            'content' => 'An error occured while submitting your registration. Please refresh the page and try again.',
                        ];
                        Session::flash('site_errors', $course_errors);
                        return redirect()->to('/login');
                    } else {

                        $randomString =  $this->generateRandomString(9);

                        $position = new TwillPosition();
                        $position->user_id = $newUser->id;
                        $position->position = $randomString;
                        $position->save();


                        $edxResponse =  $this->edxRepository->edxRegister($newUser, $position->position);

                        if ($edxResponse !== true) {

                            $course_errors = [
                                'title' => 'Sorry',
                                'content' => 'An error occured while submitting your registration. Please refresh the page and try again.',
                            ];

                            Session::flash('site_errors', $course_errors);

                            return redirect()->to('/login');
                        } else {
       

                            $edxuser = EdxAuthUser::where('username', $newUser->username)->first();

                            $edxuser->first_name = $newUser->first_name;
                            $edxuser->last_name = $newUser->last_name;
                            $edxuser->email = $newUser->email;
                            $edxuser->is_active =  1;
                            $edxuser->save();


                          
                            Auth::login($newUser, true);

                            $edxStatus = $this->edxRepository->edxLogin($newUser, $position->position);

                            if (!$edxStatus) {
                                Auth::logout();
                                $course_errors = [
                                    'title' => 'Sorry',
                                    'content' => 'An error occured while submitting your registration. Please refresh the page and try again.',
                                ];

                                Session::flash('site_errors', $course_errors);

                                return redirect()->to('/login');
                            } else {
                             
                                 $this->saveAccessToken($finduser, $user);

                                $newUser->last_login_at = Carbon::now();
                                $newUser->reset_pd = 1;
                                $newUser->save();

                                return redirect()->route('pages', ['key' => 'courses']);
                            }
                        }
                    }
                }
            } else {
                $course_errors = [
                    'title' => 'Sorry',
                    'content' => 'The email or domain is not allowed for this platform',
                ];

                Session::flash('site_errors', $course_errors);
                return redirect()->to('/login');
            }
        } catch (Exception $e) {

            return redirect('/login');
        }
    }




    protected function getRepository()
    {
        return App::make("App\\Repositories\\UserRepository");
    }


    protected function getEdxRepository()
    {
        return App::make("App\\Repositories\\AuthenticateEdxRepository");
    }


    protected function generateRandomString($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }


    protected function saveAccessToken($user, $microsoftUser)
    {
        // Log::info('Starting saveAccessToken');
        try {
            // Log::info('google user',[$googleUser]);
            $tokenData = [
                'user_id' => $user->id,
                'provider' => 'google',
                'access_token' => $microsoftUser->token,
                'expires_at' => Carbon::now()->addSeconds($microsoftUser->expiresIn ?? 3600)->timestamp,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // Only add refresh token if it exists
            if ($microsoftUser->refreshToken) {
                $tokenData['refresh_token'] = $microsoftUser->refreshToken;
            } else {
                Log::warning('No refresh token received for user', ['user_id' => $user->id]);
            }

            MicrositeAccessToken::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'provider' => 'google',
                ],
                $tokenData
            );
            // Log::info('Completed saveAccessToken successfully');
        } catch (\Exception $e) {
            Log::error('Error in saveAccessToken: ' . $e->getMessage());
            // Re-throw or handle the exception as needed
        }
    }
}
