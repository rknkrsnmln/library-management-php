<?php

namespace Library\PHP\MVC\Controller;


use Library\PHP\MVC\App\View;
use Library\PHP\MVC\Config\Database;
use Library\PHP\MVC\Exception\ValidationException;
use Library\PHP\MVC\Model\UserLoginRequest;
use Library\PHP\MVC\Model\UserPasswordUpdateRequest;
use Library\PHP\MVC\Model\UserProfileUpdateRequest;
use Library\PHP\MVC\Model\UserRegisterRequest;
use Library\PHP\MVC\Repository\SessionRepository;
use Library\PHP\MVC\Repository\UserRepository;
use Library\PHP\MVC\Service\SessionService;
use Library\PHP\MVC\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function register(): void
    {
        View::render('User/register', [
            'title' => 'Register new User'
        ]);
    }

    public function postRegister(): void
    {
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];
        $request->role = $_POST['role'] ?? 'member';
        $request->status = $_POST['status'] ?? 'active';

        try {
            $this->userService->register($request);
            View::redirect('/users/login');
        } catch (ValidationException $exception) {
            View::render('User/register', [
                'title' => 'Register new User',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function login(): void
    {
        View::render('User/login', [
            "title" => "Login user"
        ]);
    }

    public function postLogin(): void
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/login', [
                'title' => 'Login user',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function logout(): void
    {
        $this->sessionService->destroy();
        View::redirect("/");
    }

    public function updateProfile(): void
    {
        $user = $this->sessionService->current();
        View::renderNew('User/profile', [
            "title" => "Update user profile",
            "user" => [
                "id" => $user->id,
                "name" => $user->name
            ]
        ]);
    }

    public function postUpdateProfile(): void
    {
        $user = $this->sessionService->current();

        $request = new UserProfileUpdateRequest();
        $request->id = $user->id;
        $request->name = $_POST['name'];

        try {
            $this->userService->updateProfile($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/profile', [
                "title" => "Update user profile",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $_POST['name']
                ]
            ]);
        }
    }

    public function updatePassword(): void
    {
        $user = $this->sessionService->current();
        View::renderNew('User/password', [
            "title" => "Update user password",
            "user" => [
                "id" => $user->id,
                "name" => $user->name
            ]
        ]);
    }

    public function postUpdatePassword(): void
    {
        $user = $this->sessionService->current();
        $request = new UserPasswordUpdateRequest();
        $request->id = $user->id;
        $request->oldPassword = $_POST['oldPassword'];
        $request->newPassword = $_POST['newPassword'];

        try {
            $this->userService->updatePassword($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/password', [
                "title" => "Update user password",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name
                ]
            ]);
        }
    }
}