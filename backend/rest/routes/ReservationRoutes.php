<?php


/**
 * @OA\Get(
 *     path="/reservations",
 *     summary="Get all reservations",
 *     tags={"Reservations"},
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
Flight::route('GET /reservations', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::reservationService()->get_all());
});

/**
 * @OA\Get(
 *     path="/reservations/{id}",
 *     summary="Get reservation by ID",
 *     tags={"Reservations"},
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
Flight::route('GET /reservations/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::reservationService()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/reservations",
 *     summary="Create a new reservation",
 *     tags={"Reservations"},
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"date", "time"},
 *             @OA\Property(property="user_id", type="integer", example=1, description="User ID"),
 *             @OA\Property(property="date", type="string", format="date", example="2025-11-01", description="Reservation date (YYYY-MM-DD)"),
 *             @OA\Property(property="time", type="string", format="time", example="19:00:00", description="Reservation time (HH:MM:SS)")
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
Flight::route('POST /reservations', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reservationService()->createReservation($data));
});

/**
 * @OA\Put(
 *     path="/reservations/{id}",
 *     summary="Update reservation by ID",
 *     tags={"Reservations"},
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
 *             @OA\Property(property="user_id", type="integer", example=1, description="User ID"),
 *             @OA\Property(property="date", type="string", format="date", example="2025-11-01", description="Reservation date (YYYY-MM-DD)"),
 *             @OA\Property(property="time", type="string", format="time", example="19:00:00", description="Reservation time (HH:MM:SS)")
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
Flight::route('PUT /reservations/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reservationService()->updateReservation($data, $id));
});

/**
 * @OA\Delete(
 *     path="/reservations/{id}",
 *     summary="Delete reservation by ID",
 *     tags={"Reservations"},
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
Flight::route('DELETE /reservations/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::reservationService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/reservations/user/{user_id}",
 *     summary="Get reservations by user ID",
 *     tags={"Reservations"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="user_id",
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
Flight::route('GET /reservations/user/@user_id', function($user_id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::reservationService()->getByUserId($user_id));
});

/**
 * @OA\Get(
 *     path="/reservations/date-range",
 *     summary="Get reservations by date range",
 *     tags={"Reservations"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="start",
 *         in="query",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="end",
 *         in="query",
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
Flight::route('GET /reservations/date-range', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $start_date = Flight::request()->query['start'] ?? null;
    $end_date = Flight::request()->query['end'] ?? null;
    Flight::json(Flight::reservationService()->getByDateRange($start_date, $end_date));
});

/**
 * @OA\Get(
 *     path="/reservations/available",
 *     summary="Check if reservation slot is available",
 *     tags={"Reservations"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="time",
 *         in="query",
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
Flight::route('GET /reservations/available', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $date = Flight::request()->query['date'] ?? null;
    $time = Flight::request()->query['time'] ?? null;
    Flight::json(Flight::reservationService()->isSlotAvailable($date, $time));
});

/**
 * @OA\Delete(
 *     path="/reservations/{id}/cancel",
 *     summary="Cancel reservation by ID",
 *     tags={"Reservations"},
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
Flight::route('DELETE /reservations/@id/cancel', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::reservationService()->cancel($id));
});

/**
 * @OA\Get(
 *     path="/reservations/day/{date}",
 *     summary="Get reservations for a specific day with users",
 *     tags={"Reservations"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="date",
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
Flight::route('GET /reservations/day/@date', function($date){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::reservationService()->listForDayWithUsers($date));
});

?>

