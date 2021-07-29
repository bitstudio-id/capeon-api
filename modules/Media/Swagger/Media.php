<?php

/**
 * @license Apache 2.0
 */

namespace Modules\Media\Repositories;

/**
 * Media
 *
 * @OA\Schema(
 *     title="Media",
 *     @OA\Xml(
 *         name="Media"
 *     )
 * )
 */
class Media
{
    /**
     * @OA\Property(
     *     title="id",
     *     description="id",
     *     format="int64",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     title="Media nama",
     *     description="Media nama",
     * )
     *
     * @var string
     */
    private $nama;
}