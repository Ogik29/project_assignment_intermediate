<?php

namespace App\ContohBootcamp\Services;

use App\ContohBootcamp\Repositories\TaskRepository;

class TaskService
{
	private TaskRepository $taskRepository;

	public function __construct()
	{
		$this->taskRepository = new TaskRepository();
	}

	/**
	 * NOTE: untuk mengambil semua tasks di collection task
	 */
	public function getTasks()
	{
		$tasks = $this->taskRepository->getAll();
		return $tasks;
	}

	/**
	 * NOTE: menambahkan task
	 */
	public function addTask(array $data)
	{
		$taskId = $this->taskRepository->create($data);
		return $taskId;
	}

	/**
	 * NOTE: UNTUK mengambil data task
	 */
	public function getById(string $taskId)
	{
		$task = $this->taskRepository->getById($taskId);
		return $task;
	}

	/**
	 * NOTE: untuk update task
	 */
	public function updateTask(array $editTask, array $formData)
	{
		if (isset($formData['title'])) {
			$editTask['title'] = $formData['title'];
		}

		if (isset($formData['description'])) {
			$editTask['description'] = $formData['description'];
		}

		$id = $this->taskRepository->save($editTask);
		return $id;
	}

	public function deleteTask($taskId)
	{
		$task = $this->taskRepository->delete($taskId);
		return $task;
	}

	public function assignTask($task, $assigned)
	{
		$task['assigned'] = $assigned;
		$id = $this->taskRepository->save($task);
		return $id;
	}

	public function unassign_task($task)
	{
		$task['assigned'] = null;
		$id = $this->taskRepository->save($task);
		return $id;
	}

	public function create_subtasks($task, $data)
	{
		$id = $this->taskRepository->create_subtasks($task, $data);
		return $id;
	}

	public function delete_subtask($task, $subtaskId)
	{
		$id = $this->taskRepository->delete_subtask($task, $subtaskId);
		return $id;
	}
}
