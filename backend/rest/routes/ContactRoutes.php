<?php

/**
 * @OA\Get(
 *     path="/contacts",
 *     summary="Get all contacts",
 *     tags={"Contacts"},
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
Flight::route('GET /contacts', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::contactService()->get_all());
});

/**
 * @OA\Get(
 *     path="/contacts/{id}",
 *     summary="Get contact by ID",
 *     tags={"Contacts"},
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
Flight::route('GET /contacts/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::contactService()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/contacts",
 *     summary="Create a new contact",
 *     tags={"Contacts"},
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"user_name", "user_email"},
 *             @OA\Property(property="user_name", type="string", example="John Doe", description="Contact person name"),
 *             @OA\Property(property="user_email", type="string", format="email", example="john@example.com", description="Contact person email"),
 *             @OA\Property(property="subject", type="string", example="Question about menu", description="Message subject"),
 *             @OA\Property(property="message", type="string", example="I would like to know more about your menu options.", description="Message content")
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
Flight::route('POST /contacts', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::contactService()->add($data));
});

/**
 * @OA\Put(
 *     path="/contacts/{id}",
 *     summary="Update contact by ID",
 *     tags={"Contacts"},
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
 *             @OA\Property(property="user_name", type="string", example="John Doe", description="Contact person name"),
 *             @OA\Property(property="user_email", type="string", format="email", example="john@example.com", description="Contact person email"),
 *             @OA\Property(property="subject", type="string", example="Question about menu", description="Message subject"),
 *             @OA\Property(property="message", type="string", example="I would like to know more about your menu options.", description="Message content")
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
Flight::route('PUT /contacts/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::contactService()->update($data, $id));
});

/**
 * @OA\Delete(
 *     path="/contacts/{id}",
 *     summary="Delete contact by ID",
 *     tags={"Contacts"},
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
Flight::route('DELETE /contacts/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::contactService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/contacts/email/{email}",
 *     summary="Get contact by email",
 *     tags={"Contacts"},
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
Flight::route('GET /contacts/email/@email', function($email){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::contactService()->getByEmail($email));
});

/**
 * @OA\Get(
 *     path="/contacts/search/subject/{term}",
 *     summary="Search contacts by subject",
 *     tags={"Contacts"},
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
Flight::route('GET /contacts/search/subject/@term', function($term){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::contactService()->searchBySubject($term));
});

/**
 * @OA\Get(
 *     path="/contacts/recent",
 *     summary="Get recent contacts",
 *     tags={"Contacts"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="offset",
 *         in="query",
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
Flight::route('GET /contacts/recent', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $limit = Flight::request()->query['limit'] ?? 10;
    $offset = Flight::request()->query['offset'] ?? 0;
    Flight::json(Flight::contactService()->getRecent($limit, $offset));
});

?>

