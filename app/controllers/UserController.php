<?php

namespace app\controllers;

use app\models\User;
use core\auth\JWT;
use core\library\Request;
use core\library\Response;
use database\Connection;
use database\repository\PdoUserRepository;

class UserController
{

    /**
     * @throws \Exception
     */
    public function index(Request $request): array|Response
    {
        if(JWT::verify($request->authorizationToken)){
            $userRepository = new PdoUserRepository(Connection::Connection());
            $users = $userRepository->all();
            return [
                'error' => false,
                'success' => true,
                'users' => $users
            ];
        }

        return response(
            json_encode([
                    'error' => true,
                    'success' => false,
                    'message' => 'Token Invalid!'
                ]
            ),
            400,
            ['content-type' => 'application/json']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): array|Response
    {
        $error = false;
        $msg = 'Token Invalid!';
        if(JWT::verify($request->authorizationToken)){
            $error = true;
            $msg = 'Body Empty';
            foreach ($request->body as $field => $value) {
                if (empty(trim($value))){
                    $error = false;
                    $msg = $field . ' cannot be empty';
                }
            }

            if (count($request->body) > 0 && $error) {
                $userRepository = new PdoUserRepository(Connection::Connection());
                $user = $userRepository->store(new User(
                    null,
                    $request->body['name'],
                    $request->body['email'],
                    password_hash($request->body['password'], PASSWORD_DEFAULT),
                ));

                if ($user->getId()) {
                    return [
                        'error' => false,
                        'success' => true,
                        'user' => [
                            'id' => $user->getId(),
                            'name' => $request->body['name'],
                            'email' => $request->body['email'],
                        ]
                    ];
                }
            }
        }
        return response(
            json_encode([
                    'error' => $error,
                    'success' => !$error,
                    'message' => $msg
                ]
            ),
            $error ? 400 : 200,
            ['content-type' => 'application/json']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): array|Response
    {
        $error = false;
        $msg = 'Token Invalid!';
        if(JWT::verify($request->authorizationToken)){
            $error = true;
            $msg = 'Body Empty';
            foreach ($request->body as $field => $value) {
                if (empty(trim($value))){
                    $error = false;
                    $msg = $field . ' cannot be empty';
                }
            }

            if (count($request->body) > 0 && $error) {
                $userRepository = new PdoUserRepository(Connection::Connection());
                $user = $userRepository->find($request->body['id']);

                if ($user) {
                    return [
                        'error' => false,
                        'success' => true,
                        'user' => [
                            'id' => $user->getId(),
                            'name' => $user->getName(),
                            'email' => $user->getEmail(),
                        ]
                    ];
                }
                $msg = 'User not found';
            }
        }
        return response(
            json_encode([
                    'error' => $error,
                    'success' => !$error,
                    'message' => $msg
                ]
            ),
            $error ? 400 : 200,
            ['content-type' => 'application/json']);
    }

    public function authenticate(Request $request): array|Response
    {
        $error = true;
        $msg = 'Body Empty';
        foreach ($request->body as $field => $value) {
            if (empty(trim($value))){
                $error = false;
                $msg = $field . ' cannot be empty';
            }
        }

        if (count($request->body) > 0 && $error) {
            $userRepository = new PdoUserRepository(Connection::Connection());
            $user = $userRepository->auth($request->body['email'], $request->body['password']);

            if ($user) {
                return [
                    'error' => false,
                    'success' => true,
                    'user' => [
                        'id' => $user->getId(),
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                    ],
                    'token' => JWT::generate([
                        'id' => $user->getId(),
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                    ])
                ];
            }
            $msg = 'User not found';
        }

        return response(
            json_encode([
                    'error' => $error,
                    'success' => !$error,
                    'message' => $msg
                ]
            ),
            $error ? 400 : 200,
            ['content-type' => 'application/json']);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}