@extends('layouts.project')

@section('__content')
  <div class="boards-container">
    <div class="status-group-board">
      <h5>No Status</h5>
    </div>
    @foreach ($project->statusGroups as $group)
      <div class="status-group-board">
        <h5>{{ $group->name }}</h5>
      </div>
    @endforeach
  </div>
@endsection

@section('__scripts')
  <script>
    let todos = document.querySelectorAll('.todo');
    let statuses = document.querySelectorAll('.status-group-board');
    let selectedTodo = null;

    todos.forEach(todo => {
      todo.addEventListener('dragstart', dragStart);
      todo.addEventListener('dragend', dragEnd);
    });

    statuses.forEach(status => {
      status.addEventListener('dragover', dragOver);
      status.addEventListener('dragenter', dragEnter);
      status.addEventListener('dragleave', dragLeave);
      status.addEventListener('drop', drop);
    })

    function dragStart () {
      selectedTodo = this;
      console.log('dragstart');
    }

    function dragEnd () {
      selectedTodo = null;
      console.log('dragend');
    }

    function dragOver (e) {
      e.preventDefault();
    }
    
    function dragEnter () {
      console.log('dragenter');
    }

    function dragLeave () {
      console.log('dragleave');
    }

    function drop () {
      this.appendChild(selectedTodo);
      console.log('dropped');
    }
  </script>
@endsection