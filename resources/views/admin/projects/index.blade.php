@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<header class="d-flex align-items-center justify-content-between pb-4 mb-4 mt-3 border-bottom">
    <h1>Projects</h1>

    <form action="{{ route('admin.projects.index') }}" method="GET">
        <div class="input-group">
            <select class="form-select" name="type_filter">
                <option value="">Types</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" @if($type_filter == $type->id) selected @endif>{{ $type->label }}</option>
                @endforeach
            </select>
            <select class="form-select" name="technology_filter">
                <option value="">Technologies</option>
                @foreach ($technologies as $technology)
                    <option value="{{ $technology->id }}" @if($technology_filter == $technology->id) selected @endif>{{ $technology->label }}</option>
                @endforeach
            </select>
            <select class="form-select" name="completed_filter">
                <option value="">Status</option>
                <option value="completed" @if($completed_filter === 'completed') selected @endif>Completed</option>
                <option value="drafts" @if($completed_filter === 'drafts') selected @endif>Work in progress</option>
            </select>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary" type="reset">Reset</a>
            <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-filter me-2"></i>Filter</button>
        </div>
    </form>
</header>

<table class="table table-hover table-secondary table-striped border mb-4">
    <thead class="table-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Slug</th>
            <th scope="col">Type</th>
            <th scope="col">Technologies</th>
            <th scope="col">Status</th>
            <th scope="col">Created</th>
            <th scope="col">Updated</th>
            <th>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.projects.trash') }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-trash me-2"></i>Trash</a>

                    <a href="{{ route('admin.projects.create') }}" class="btn btn-sm btn-success"><i class="fa-solid fa-plus me-2"></i>Project</a>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>

        @forelse($projects as $project)
        <tr>
            <th scope="row">{{ $project->id }}</th>
            <td>{{ $project->title }}</td>
            <td>{{ $project->slug }}</td>
            <td>@if ($project->type)
                    <span class="badge align-middle" style="background-color: {{ $project->type->color }}">{{ $project->type->label }}</span>
                @else
                    Nothing
                @endif
            </td>
            <td>
                @forelse ($project->technologies as $technology)
                    <span class="align-middle mx-2" style="color: {{ $technology->color }}"><i class="{{ $technology->icon }} h3 m-0"></i></span>
                @empty
                    Nothing
                @endforelse
            </td>
            <td>
                <form action="{{ route('admin.projects.complete', $project->id) }}" method="POST" class="completion-form">
                    @csrf
                    @method('PATCH')
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="button" id="is_completed" @if($project->is_completed) checked @endif>
                    </div>
                </form>
            </td>
            <td>{{ $project->getFormattedDate('created_at') }}</td>
            <td>{{ $project->getFormattedDate('updated_at') }}</td>
            <td>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pencil"></i></a>

                    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="delete-form" data-bs-toggle="modal" data-bs-target="#modal" data-project="{{ $project->title }}">
                        @csrf
                        @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-regular fa-trash-can"></i></button>
                    </form>
                </div>
            </td>
        </tr>

        @empty 
            <tr>
                <td colspan="9">
                    <h3 class="text-center">There aren't any projects.</h3>
                </td>
            </tr>
        @endforelse

    </tbody>
</table>

@if($projects->hasPages())
    {{ $projects->links() }}
@endif

@endsection

@section('scripts')
  @vite('resources/js/delete_confirmation.js')

  <script>
    const toggleCompletionForms = document.querySelectorAll('.completion-form')

    toggleCompletionForms.forEach(form => {
        form.addEventListener('click', () => {
            form.submit();
        });
    })
  </script>
@endsection