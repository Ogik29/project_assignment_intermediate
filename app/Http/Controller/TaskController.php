<?php

namespace App\Http\Controller;

use App\ContohBootcamp\Services\TaskService;
use App\Helpers\MongoModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
	private TaskService $taskService;
	public function __construct()
	{
		$this->taskService = new TaskService();
	}

	public function showTasks()
	{
		$tasks = $this->taskService->getTasks();
		return response()->json($tasks);
	}

	public function createTask(Request $request)
	{
		$request->validate([
			'title' => 'required|string|min:3',
			'description' => 'required|string'
		]);

		$data = [
			'title' => $request->post('title'),
			'description' => $request->post('description')
		];

		$dataSaved = [
			'title' => $data['title'],
			'description' => $data['description'],
			'assigned' => null,
			'subtasks' => [],
			'created_at' => time()
		];

		$id = $this->taskService->addTask($dataSaved);
		$task = $this->taskService->getById($id);

		return response()->json($task);
	}


	public function updateTask(Request $request)
	{
		$request->validate([
			'task_id' => 'required|string',
			'title' => 'string',
			'description' => 'string',
			'assigned' => 'string',
			'subtasks' => 'array',
		]);

		$taskId = $request->post('task_id');
		$formData = $request->only('title', 'description', 'assigned', 'subtasks');
		$task = $this->taskService->getById($taskId);

		$this->taskService->updateTask($task, $formData);

		$task = $this->taskService->getById($taskId);

		return response()->json($task);
	}


	// TODO: deleteTask()
	public function deleteTask(Request $request)
	{
		$request->validate([
			'task_id' => 'required'
		]);

		$taskId = $request->task_id;

		// get data from service
		$task = $this->taskService->getById($taskId);

		if (!$task) {
			return response()->json([
				"message" => "Task " . $taskId . " tidak ada"
			], 401);
		}

		// call method from service
		$this->taskService->deleteTask($taskId);

		return response()->json([
			'message' => 'Success delete task ' . $taskId
		]);
	}

	// TODO: assignTask()
	public function assignTask(Request $request)
	{
		$request->validate([
			'task_id' => 'required',
			'assigned' => 'required'
		]);

		$taskId = $request->get('task_id');
		$assigned = $request->post('assigned');

		$task = $this->taskService->getById($taskId);

		if (!$task) {
			return response()->json([
				"message" => "Task " . $taskId . " tidak ada"
			], 401);
		}

		$this->taskService->assignTask($task, $assigned);

		$task_updated_assigned = $this->taskService->getById($taskId);

		return response()->json($task_updated_assigned);
	}

	// TODO: unassignTask()
	public function unassignTask(Request $request)
	{
		$request->validate([
			'task_id' => 'required'
		]);

		$taskId = $request->post('task_id');
		$task = $this->taskService->getById($taskId);

		if (!$task) {
			return response()->json([
				"message" => "Task " . $taskId . " tidak ada"
			], 401);
		}

		$this->taskService->unassign_task($task);

		$task_unassigned = $this->taskService->getById($taskId);

		return response()->json($task_unassigned);
	}

	// TODO: createSubtask()
	public function createSubtask(Request $request)
	{
		$request->validate([
			'task_id' => 'required',
			'title' => 'required|string',
			'description' => 'required|string'
		]);

		$taskId = $request->post('task_id');
		$data = [
			'title' => $request->post('title'),
			'description' => $request->post('description')
		];

		$task = $this->taskService->getById($taskId);

		if (!$task) {
			return response()->json([
				"message" => "Task " . $taskId . " tidak ada"
			], 401);
		}

		$this->taskService->create_subtasks($task, $data);

		$task_created_subtasks = $this->taskService->getById($taskId);

		return response()->json($task_created_subtasks);
	}

	// TODO deleteSubTask()
	public function deleteSubtask(Request $request)
	{
		$request->validate([
			'task_id' => 'required',
			'subtask_id' => 'required'
		]);

		$taskId = $request->post('task_id');
		$subtaskId = $request->post('subtask_id');

		$task = $this->taskService->getById($taskId);

		if (!$task) {
			return response()->json([
				"message" => "Task " . $taskId . " tidak ada"
			], 401);
		}

		$this->taskService->delete_subtask($task, $subtaskId);

		$task_delete_subtasks = $this->taskService->getById($taskId);

		return response()->json($task_delete_subtasks);
	}
}
