<?php

namespace Library\PHP\MVC\Service;



use Exception;
use Library\PHP\MVC\Config\Database;
use Library\PHP\MVC\Domain\User;
use Library\PHP\MVC\Exception\ValidationException;
use Library\PHP\MVC\Model\UserLoginRequest;
use Library\PHP\MVC\Model\UserLoginResponse;
use Library\PHP\MVC\Model\UserPasswordUpdateRequest;
use Library\PHP\MVC\Model\UserPasswordUpdateResponse;
use Library\PHP\MVC\Model\UserProfileUpdateRequest;
use Library\PHP\MVC\Model\UserProfileUpdateResponse;
use Library\PHP\MVC\Model\UserRegisterRequest;
use Library\PHP\MVC\Model\UserRegisterResponse;
use Library\PHP\MVC\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws ValidationException
     */
    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if ($user != null) {
                throw new ValidationException("User Id already exists");
            }

            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $user->role = $request->role;
            $user->status = $request->status;

            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->user = $user;

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserRegistrationRequest(UserRegisterRequest $request): void
    {
        if ($request->id == null || $request->name == null || $request->password == null ||
            trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == "") {
            throw new ValidationException("Id, Name, Password can not blank");
        }
    }

    /**
     * @throws ValidationException
     */
    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findById($request->id);
        if ($user == null) {
            throw new ValidationException("Id or password is wrong");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("Id or password is wrong");
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserLoginRequest(UserLoginRequest $request): void
    {
        if ($request->id == null || $request->password == null ||
            trim($request->id) == "" || trim($request->password) == "") {
            throw new ValidationException("Id, Password can not blank");
        }
    }

    /**
     * @throws ValidationException
     */
    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
    {
        $this->validateUserProfileUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if ($user == null) {
                throw new ValidationException("User is not found");
            }

            $user->name = $request->name;
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserProfileUpdateResponse();
            $response->user = $user;
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserProfileUpdateRequest(UserProfileUpdateRequest $request): void
    {
        if ($request->id == null || $request->name == null ||
            trim($request->id) == "" || trim($request->name) == "") {
            throw new ValidationException("Id, Name can not blank");
        }
    }

    /**
     * @throws ValidationException
     */
    public function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse
    {
        $this->validateUserPasswordUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if ($user == null) {
                throw new ValidationException("User is not found");
            }

            if (!password_verify($request->oldPassword, $user->password)) {
                throw new ValidationException("Old password is wrong");
            }

            $user->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserPasswordUpdateResponse();
            $response->user = $user;
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserPasswordUpdateRequest(UserPasswordUpdateRequest $request): void
    {
        if ($request->id == null || $request->oldPassword == null || $request->newPassword == null ||
            trim($request->id) == "" || trim($request->oldPassword) == "" || trim($request->newPassword) == "") {
            throw new ValidationException("Id, Old Password, New Password can not blank");
        }
    }

    /**
     * Get all books.
     * @return User[]
     * @throws Exception
     */
    public function findAll(): array
    {
        try {
            return $this->userRepository->findAllUsers();
        } catch (\PDOException $e) {
            throw new Exception("An error occurred while fetching all books: " . $e->getMessage());
        }
    }
}