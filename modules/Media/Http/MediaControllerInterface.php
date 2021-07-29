<?php 
namespace Modules\Media\Http;

use Illuminate\Http\Request;
use Modules\Media\Http\MediaStoreRequest;

interface MediaControllerInterface {
	/**
	 * @OA\Get(
	 *     path="/media",
	 *     tags={"Media"},
	 *     summary="Finds Pets by status",
	 *     operationId="index",
	 *     description="get all media",
	 *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status values that needed to be considered for filter",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             default="available",
     *             @OA\Items(
     *                 type="string",
     *                 enum = {"available", "pending", "sold"},
     *             )
     *         )
     *     ),
	 *     @OA\Response(response="default", description="lorem ipsum")
	 * )
	 */
	public function store(Request $request);
	
	/**
     * Add a new Media
     * 
     * @OA\Post(
     *     path="/media",
     *     tags={"Media"},
     *     operationId="store",
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input"
     *     ),
     *     requestBody={"$ref": "#/components/requestBodies/Media"}
     * )
     */
	public function store(MediaStoreRequest $request);
}