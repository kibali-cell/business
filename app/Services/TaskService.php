<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskTemplate;
use App\Models\User;

class TaskService
{
    /**
     * Automatically assign a task to a random employee.
     *
     * @param array $taskData
     * @return \App\Models\Task
     */
    public function assignTaskAutomatically($taskData)
    {
        // Fetch a random user with the role of 'employee'
        $user = User::where('role', 'employee')->inRandomOrder()->first();

        if (!$user) {
            throw new \Exception('No employee available to assign the task.');
        }

        // Assign the task to the selected user
        $taskData['assigned_to'] = $user->id;

        if (!empty($taskData['template_id'])) {
            $template = TaskTemplate::find($taskData['template_id']);
            $taskData = array_merge($taskData, [
                'title' => $template->name,
                'description' => $template->description,
                'checklist' => $template->checklist
            ]);
        }

        // Create and return the task
        return Task::create($taskData);
    }
}
