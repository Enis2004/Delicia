<?php


/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
Flight::route('GET /users', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $users = Flight::userService()->get_all();
    Flight::json($users);
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get user by ID",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
Flight::route('GET /users/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $user = Flight::userService()->get_by_id($id);
    Flight::json($user);
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Update user by ID",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="John Doe", description="User full name"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com", description="User email address (unique)"),
 *             @OA\Property(property="password", type="string", format="password", example="password123", description="User password"),
 *             @OA\Property(property="role", type="string", enum={"admin", "customer"}, example="customer", description="User role")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    $user = Flight::userService()->update($data, $id);
    unset($user['password']);
    Flight::json($user);
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete user by ID",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::userService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/users/email/{email}",
 *     summary="Get user by email",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="email",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
Flight::route('GET /users/email/@email', function($email){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $user = Flight::userService()->getByEmail($email);
    Flight::json($user);
});

/**
 * @OA\Get(
 *     path="/users/role/{role}",
 *     summary="Get users by role",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="role",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
Flight::route('GET /users/role/@role', function($role){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $users = Flight::userService()->listByRole($role);
    Flight::json($users);
});

/**
 * @OA\Get(
 *     path="/users/search/{term}",
 *     summary="Search users by name or email",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="term",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
Flight::route('GET /users/search/@term', function($term){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $users = Flight::userService()->searchByNameOrEmail($term);
    Flight::json($users);
});

/**
 * @OA\Put(
 *     path="/users/{id}/change-password",
 *     summary="Change user password",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"password"},
 *             @OA\Property(property="password", type="string", format="password", example="newpassword123", description="New password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
Flight::route('PUT /users/@id/change-password', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    $password = $data['password'] ?? '';
    Flight::json(Flight::userService()->changePassword($id, $password));
});
/**
*@OA\Post(
*path="/users",
*tags={"Users"},
*summary="Insert a new user",
*security={{"ApiKey": {}}},
*@OA\RequestBody(
*required=true,
*@OA\JsonContent(
*required={"name","email","password"},
*@OA\Property(property="name", type="string", example="John Doe"),
*@OA\Property(property="email", type="string", example="john.doe@example.com"),
*@OA\Property(property="password", type="string", example="test123"),
*@OA\Property(property="role", type="string", example="user")
*)
*),
*@OA\Response(
*response=200,
*description="User created successfully"
*),
*@OA\Response(response=500, description="Server error")
*)
*/
Flight::route('POST /users', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->insertUser($data));
});
?>

