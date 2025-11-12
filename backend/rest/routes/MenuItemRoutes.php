<?php

/**
 * @OA\Get(
 *     path="/menu-items",
 *     summary="Get all menu items",
 *     tags={"Menu Items"},
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
Flight::route('GET /menu-items', function(){
    Flight::json(Flight::menuItemService()->get_all());
});

/**
 * @OA\Get(
 *     path="/menu-items/{id}",
 *     summary="Get menu item by ID",
 *     tags={"Menu Items"},
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
Flight::route('GET /menu-items/@id', function($id){
    Flight::json(Flight::menuItemService()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/menu-items",
 *     summary="Create a new menu item",
 *     tags={"Menu Items"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name", "price"},
 *             @OA\Property(property="name", type="string", example="Grilled Salmon", description="Menu item name"),
 *             @OA\Property(property="description", type="string", example="Served with lemon butter sauce", description="Menu item description"),
 *             @OA\Property(property="price", type="number", format="decimal", example=18.50, description="Menu item price (must be positive)"),
 *             @OA\Property(property="category_id", type="integer", example=1, description="Category ID")
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
Flight::route('POST /menu-items', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::menuItemService()->createMenuItem($data));
});

/**
 * @OA\Put(
 *     path="/menu-items/{id}",
 *     summary="Update menu item by ID",
 *     tags={"Menu Items"},
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
 *             @OA\Property(property="name", type="string", example="Grilled Salmon", description="Menu item name"),
 *             @OA\Property(property="description", type="string", example="Served with lemon butter sauce", description="Menu item description"),
 *             @OA\Property(property="price", type="number", format="decimal", example=18.50, description="Menu item price (must be positive)"),
 *             @OA\Property(property="category_id", type="integer", example=1, description="Category ID")
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
Flight::route('PUT /menu-items/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::menuItemService()->updateMenuItem($data, $id));
});

/**
 * @OA\Delete(
 *     path="/menu-items/{id}",
 *     summary="Delete menu item by ID",
 *     tags={"Menu Items"},
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
Flight::route('DELETE /menu-items/@id', function($id){
    Flight::json(Flight::menuItemService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/menu-items/category/{category_id}",
 *     summary="Get menu items by category ID",
 *     tags={"Menu Items"},
 *     @OA\Parameter(
 *         name="category_id",
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
Flight::route('GET /menu-items/category/@category_id', function($category_id){
    Flight::json(Flight::menuItemService()->getByCategoryId($category_id));
});

/**
 * @OA\Get(
 *     path="/menu-items/search/{term}",
 *     summary="Search menu items by name",
 *     tags={"Menu Items"},
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
Flight::route('GET /menu-items/search/@term', function($term){
    Flight::json(Flight::menuItemService()->searchByName($term));
});

/**
 * @OA\Get(
 *     path="/menu-items/price-range",
 *     summary="Filter menu items by price range",
 *     tags={"Menu Items"},
 *     @OA\Parameter(
 *         name="min",
 *         in="query",
 *         @OA\Schema(type="number")
 *     ),
 *     @OA\Parameter(
 *         name="max",
 *         in="query",
 *         @OA\Schema(type="number")
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
Flight::route('GET /menu-items/price-range', function(){
    $min_price = Flight::request()->query['min'] ?? 0;
    $max_price = Flight::request()->query['max'] ?? 999999;
    Flight::json(Flight::menuItemService()->filterByPriceRange($min_price, $max_price));
});

/**
 * @OA\Get(
 *     path="/menu-items/{id}/with-category",
 *     summary="Get menu item with category by ID",
 *     tags={"Menu Items"},
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
Flight::route('GET /menu-items/@id/with-category', function($id){
    Flight::json(Flight::menuItemService()->getWithCategory($id));
});

/**
 * @OA\Post(
 *     path="/menu-items/many",
 *     summary="Get multiple menu items by IDs",
 *     tags={"Menu Items"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"ids"},
 *             @OA\Property(property="ids", type="array", @OA\Items(type="integer"), example={1, 2, 3}, description="Array of menu item IDs")
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
Flight::route('POST /menu-items/many', function(){
    $data = Flight::request()->data->getData();
    $ids = $data['ids'] ?? [];
    Flight::json(Flight::menuItemService()->getManyByIds($ids));
});

?>

