<?php

namespace App\ContohBootcamp\Repositories;

use App\Helpers\MongoModel;

class TaskRepository
{
	private MongoModel $tasks;
	public function __construct()
	{
		$this->tasks = new MongoModel('tasks');
	}

	/**
	 * Untuk mengambil semua tasks
	 */
	public function getAll()
	{
		$tasks = $this->tasks->get([]);
		return $tasks;
	}

	/**
	 * Untuk mendapatkan task bedasarkan id
	 *  */
	public function getById(string $id)
	{
		$task = $this->tasks->find(['_id' => $id]);
		return $task;
	}

	/**
	 * Untuk membuat task
	 */
	public function create(array $data)
	{
		$dataSaved = [
			'title' => $data['title'],
			'description' => $data['description'],
			'assigned' => null,
			'subtasks' => [],
			'created_at' => time()
		];

		$id = $this->tasks->save($dataSaved);
		return $id;
	}

	/**
	 * Untuk menyimpan task baik untuk membuat baru atau menyimpan dengan struktur bson secara bebas
	 *  */
	public function save(array $editedData)
	{
		$id = $this->tasks->save($editedData);
		return $id;
	}

	public function delete($taskId)
	{
		$id = $this->tasks->deleteQuery(['_id' => $taskId]);
		return $id;
	}

	public function create_subtasks($task, $data)
	{
		$subtasks = isset($task['subtasks']) ? $task['subtasks'] : [];

		$subtasks[] = [
			'_id' => (string) new \MongoDB\BSON\ObjectId(),
			'title' => $data['title'],
			'description' => $data['description']
		];

		$task['subtasks'] = $subtasks;
		$id = $this->tasks->save($task);

		return $id;
	}

	public function delete_subtask($task, $subtaskId)
	{
		$subtasks = isset($task['subtasks']) ? $task['subtasks'] : [];

		$subtasks = array_filter($subtasks, function ($subtask) use ($subtaskId) {

			if ($subtask['_id'] == $subtaskId) {
				return false; // hapus dari array
			} else {
				return true;
			}
		});

		$subtasks = array_values($subtasks);
		$task['subtasks'] = $subtasks;

		$id = $this->tasks->save($task);
		return $id;
	}
}
