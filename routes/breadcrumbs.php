<?php

use App\Models\Project;
use App\Models\Task;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
  $trail->push('Home', route('home'));
});

Breadcrumbs::for('projects', function (BreadcrumbTrail $trail) {
  $trail->parent('home');
  $trail->push('Projects', route('projects.index'));
});

Breadcrumbs::for('project', function (BreadcrumbTrail $trail, Project $project) {
  $trail->parent('projects');
  $trail->push($project->name, route('projects.show', ['project' => $project ]));
});

Breadcrumbs::for('task', function (BreadcrumbTrail $trail, Project $project, Task $task) {
  $trail->parent('project', $project);
  $trail->push($task->title, route('projects.tasks.show', ['project' => $project, 'task' => $task]));
});


