<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCarRequest;
use Symfony\Component\HttpFoundation\Response;

class CarController extends Controller
{
    /**
     * Display a listing of the cars.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $car = Car::all();

        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    /**
     * Store a newly created car in storage.
     *
     * @param StoreCarRequest $request
     * @return JsonResponse
     */
    public function store(StoreCarRequest $request): JsonResponse
    {
        $data = $request->validated();

        $car = Car::query()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Car created successfully',
            'data' => $car
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified car.
     *
     * @param Car $car
     * @return JsonResponse
     */
    public function show(Car $car): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    /**
     * Update the specified car in storage.
     *
     * @param UpdateCarRequest $request
     * @param Car $car
     * @return JsonResponse
     */
    public function update(UpdateCarRequest $request, Car $car): JsonResponse
    {
        $data = $request->validated();

        $car->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Car updated successfully',
            'data' => $car
        ]);
    }

    /**
     * Remove the specified car from storage.
     *
     * @param Car $car
     * @return JsonResponse
     */
    public function destroy(Car $car): JsonResponse
    {
        $car->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
