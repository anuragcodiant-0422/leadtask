@extends('layout')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-4">
        <div>
            <p class="text-uppercase text-primary font-weight-bold mb-2" style="font-size: 12px; letter-spacing: .12em;">{{ __('Your workspace') }}</p>


        </div>
        <div class="mt-4 mt-md-0 text-md-right">


        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4" role="alert">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4" role="alert">
            <strong>{{ __('Please check the highlighted fields.') }}</strong>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body p-4 p-md-5">
            <div class="mb-4">
                <h2 class="h4 mb-1">{{ __('Lead Creation') }}</h2>
                <p class="text-muted small mb-0">{{ __('Create a new lead by filling in the details below.') }}</p>
            </div>
            <form method="POST" action="{{ route('create.lead') }}" >
                @csrf
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4 mb-3">
                        <label for="first_name" class="font-weight-bold small">{{ __('First Name') }}</label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" maxlength="255" required class="form-control @error('first_name') is-invalid @enderror" placeholder="{{ __('First Name') }}" required>
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="last_name" class="font-weight-bold small">{{ __('Last Name') }}</label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" maxlength="255" required class="form-control @error('last_name') is-invalid @enderror" placeholder="{{ __('Last Name') }}" required>
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="email" class="font-weight-bold small">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="255" required class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email') }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="phone" class="font-weight-bold small">{{ __('Phone') }}</label>
                        <input id="phone" type="number" name="phone" value="{{ old('phone') }}" maxlength="255" required class="form-control @error('phone') is-invalid @enderror" placeholder="{{ __('Phone') }}" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="company_name" class="font-weight-bold small">{{ __('Company Name') }}</label>
                        <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" maxlength="255" required class="form-control @error('company_name') is-invalid @enderror" placeholder="{{ __('Company Name') }}">
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="source" class="font-weight-bold small">{{ __('Source') }}</label>
                        <input id="source" type="text" name="source" value="{{ old('source') }}" maxlength="255" required class="form-control @error('source') is-invalid @enderror" placeholder="{{ __('Source') }}">
                        @error('source') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="status" class="font-weight-bold small">{{ __('Status') }}</label>
                        <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="assigned_user" class="font-weight-bold small">{{ __('Assigned User') }}</label>
                        <select id="assigned_user" name="assigned_user" class="form-control @error('assigned_user') is-invalid @enderror">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_user') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach

                        </select>
                        @error('assigned_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="note" class="font-weight-bold small">{{ __('Update Lead Information') }}</label>
                        <textarea id="note" name="note" maxlength="255" class="form-control @error('note') is-invalid @enderror" placeholder="{{ __('Update Lead Information') }}">{{ old('note') }}</textarea>
                        @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="change_status" class="font-weight-bold small">{{ __('Change Lead Status') }}</label>
                        <select id="change_status" name="change_status" class="form-control @error('change_status') is-invalid @enderror">
                            <option value="new" {{ old('change_status') == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                            <option value="contacted" {{ old('change_status') == 'contacted' ? 'selected' : '' }}>{{ __('Contacted') }}</option>
                            <option value="qualified" {{ old('change_status') == 'qualified' ? 'selected' : '' }}>{{ __('Qualified') }}</option>
                            <option value="won" {{ old('change_status') == 'won' ? 'selected' : '' }}>{{ __('Won') }}</option>
                            <option value="lost" {{ old('change_status') == 'lost' ? 'selected' : '' }}>{{ __('Lost') }}</option>
                        </select>

                        @error('change_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Create Lead') }}</button>
                    </div>

            </form>
        </div>
    </div>

</div>
@endsection
