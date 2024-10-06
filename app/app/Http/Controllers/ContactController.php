<?php

namespace App\Http\Controllers;

use App\Http\Services\QuestionService;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    protected $questionService;
    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('contact');
    }

    public function create(Request $request): JsonResponse
    {
        $validatedRequest = $request->validate([
            'name' => 'required',
            'email' => 'required|max:100',
            'message' => 'required|max:255'
        ]);

        $result = $this->questionService->sendQuestion($validatedRequest);

        if(isset($result)) {
            return response()->json($result);
        } else {
            return response()->json($result);
        }
    }

}
