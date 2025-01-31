<div class="card mt-4">
    <div class="card-header">
        <h5>Access Management</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('documents.manage-access', $document) }}" method="POST">
            @csrf
            <div class="row g-3">
                @foreach($users as $user)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="users[{{ $user->id }}][id]" 
                                   value="{{ $user->id }}" 
                                   id="user-{{ $user->id }}"
                                   {{ $document->users->contains($user) ? 'checked' : '' }}>
                            <label class="form-check-label" for="user-{{ $user->id }}">
                                {{ $user->name }}
                            </label>
                        </div>
                        <select name="users[{{ $user->id }}][permission]" 
                                class="form-select mt-2">
                            <option value="view" {{ optional($document->users->find($user->id))->pivot->permission === 'view' ? 'selected' : '' }}>
                                View Only
                            </option>
                            <option value="edit" {{ optional($document->users->find($user->id))->pivot->permission === 'edit' ? 'selected' : '' }}>
                                Can Edit
                            </option>
                            <option value="manage" {{ optional($document->users->find($user->id))->pivot->permission === 'manage' ? 'selected' : '' }}>
                                Full Management
                            </option>
                        </select>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Permissions</button>
        </form>
    </div>
</div>