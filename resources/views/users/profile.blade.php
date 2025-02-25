@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Profile</h1>
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Photo -->
            <div class="card">
                <div class="card-body text-center">
                    <div id="profile-photo-container">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                        @else
                            <img src="{{ asset('images/user.png') }}" alt="Default Profile Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                        @endif
                    </div>
                    <form id="upload-photo-form" action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="photo" id="photo" class="d-none" accept="image/*">
                        <button type="button" id="upload-photo-btn" class="btn btn-primary mt-2">Upload Photo</button>
                    </form>
                    {{-- @if($user->photo)
                        <form id="remove-photo-form" action="{{ route('profile.remove-photo') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" id="remove-photo-btn" class="btn btn-danger mt-2">Remove Photo</button>
                        </form>
                    @endif --}}
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <!-- Profile Update Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password (Leave blank to keep current password)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const uploadPhotoBtn = document.getElementById('upload-photo-btn');
        const photoInput = document.getElementById('photo');
        const uploadPhotoForm = document.getElementById('upload-photo-form');
        const removePhotoBtn = document.getElementById('remove-photo-btn');
        const removePhotoForm = document.getElementById('remove-photo-form');
        const profilePhotoContainer = document.getElementById('profile-photo-container');

        // Trigger file input when "Upload Photo" button is clicked
        uploadPhotoBtn.addEventListener('click', function () {
            photoInput.click();
        });

        // Submit the form when a file is selected
        photoInput.addEventListener('change', function () {
            if (photoInput.files.length > 0) {
                const formData = new FormData(uploadPhotoForm);

                fetch(uploadPhotoForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the profile photo dynamically
                        profilePhotoContainer.innerHTML = `<img src="${data.photo_url}" alt="Profile Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">`;
                        // Show the "Remove Photo" button
                        if (!removePhotoBtn) {
                            location.reload(); // Reload the page to show the remove button
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        // Remove the profile photo
        if (removePhotoBtn) {
            removePhotoBtn.addEventListener('click', function () {
                fetch(removePhotoForm.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the profile photo dynamically
                        profilePhotoContainer.innerHTML = `<img src="{{ asset('images/default-profile-photo.png') }}" alt="Default Profile Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">`;
                        // Hide the "Remove Photo" button
                        location.reload(); // Reload the page to hide the remove button
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    });
</script>
@endsection