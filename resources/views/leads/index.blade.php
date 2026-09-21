@extends('layout')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-4">
        <div>
            <p class="text-uppercase text-primary font-weight-bold mb-2" style="font-size: 12px; letter-spacing: .12em;">{{ __('Lead management') }}</p>
            <h1 class="h2 mb-1">{{ __('All leads') }}</h1>
            <p class="text-muted mb-0">{{ __('Search, filter, and manage your leads.') }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3 mt-md-0">{{ __('Create lead') }}</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4" role="alert">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('leads.index') }}" class="form-row align-items-end">
                <div class="form-group col-md-7 mb-3">
                    <label for="search" class="font-weight-bold small">{{ __('Search leads') }}</label>
                    <input id="search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('Name, email, phone, or company') }}">
                </div>
                <div class="form-group col-md-3 mb-3">
                    <label for="status" class="font-weight-bold small">{{ __('Status') }}</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">{{ __('All statuses') }}</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                </div>
                <div class="form-group col-md-2 mb-3 d-flex">
                    <button type="submit" class="btn btn-primary btn-block mr-2">{{ __('Filter') }}</button>
                    <a href="{{ route('leads.index') }}" class="btn btn-light" aria-label="{{ __('Clear filters') }}">&times;</a>
                </div>
            </form>
        </div>

        <div class="table-wrap">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="pl-4">{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Company') }}</th>
                        <th>{{ __('Source') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Assigned User') }}</th>
                        <th>{{ __('Lead status') }}</th>
                        <th class="text-right pr-4">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td class="pl-4 font-weight-bold">{{ $lead->first_name }} {{ $lead->last_name }}</td>
                            <td class="url-cell">{{ $lead->email }}</td>
                            <td class="url-cell">{{ $lead->phone }}</td>
                            <td class="url-cell">{{ $lead->company_name }}</td>
                            <td class="url-cell">{{ $lead->source }}</td>
                            <td>{{ ucfirst($lead->status) }}</td>
                            <td class="url-cell">{{ $lead->user->name ?? __('Unassigned') }}</td>
                            <td>
                                <span class="lead-status-value d-block mb-1">{{ ucfirst($lead->change_status) }}</span>
                                <button type="button" class="btn btn-sm btn-outline-primary status-user" data-id="{{ $lead->id }}" data-status="{{ $lead->change_status }}" data-email="{{ $lead->email }}">{{ __('Change') }}</button>
                            </td>
                            <td class="text-right pr-4">
                                <form method="POST" action="{{ route('leads.destroy', $lead->id) }}" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-5">{{ __('No leads found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center p-4">{{ $leads->links() }}</div>
        </div>
    </div>
</div>

<div class="modal-backdrop-custom d-none" id="status-modal" aria-hidden="true">
    <div class="user-modal-card status-modal-card" role="dialog" aria-modal="true" aria-labelledby="status-modal-title">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <p class="text-uppercase text-primary font-weight-bold mb-2" style="font-size: 12px; letter-spacing: .12em;">{{ __('Lead status') }}</p>
                <h2 class="h4 mb-0" id="status-modal-title">{{ __('Change lead status') }}</h2>
            </div>
            <button type="button" class="close" id="close-status-modal" aria-label="{{ __('Close') }}">&times;</button>
        </div>
        <p class="text-muted" id="status-user-email"></p>
        <form id="status-form">
            <div class="form-group">
                <label for="lead-status" class="font-weight-bold small">{{ __('Status') }}</label>
                <select id="lead-status" class="form-control" required>
                    <option value="new">{{ __('New') }}</option>
                    <option value="contacted">{{ __('Contacted') }}</option>
                    <option value="qualified">{{ __('Qualified') }}</option>
                    <option value="won">{{ __('Won') }}</option>
                    <option value="lost">{{ __('Lost') }}</option>
                </select>
            </div>
            <div id="status-error" class="invalid-feedback mb-3"></div>
            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-light mr-2" id="cancel-status-modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-primary" id="save-status">{{ __('Save status') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
const statusModal = document.getElementById('status-modal');
const statusForm = document.getElementById('status-form');
const statusSelect = document.getElementById('lead-status');
const statusError = document.getElementById('status-error');
const saveStatusButton = document.getElementById('save-status');
let statusLeadId = null;
console.log('Status modal script loaded.');
const allowedTransitions = { new: ['new', 'contacted', 'lost'], contacted: ['contacted', 'qualified', 'lost'], qualified: ['qualified', 'won', 'lost'], won: ['won'], lost: ['lost'] };

function openStatusModal(button) {
    statusLeadId = button.dataset.id;
    statusSelect.value = button.dataset.status;
    const availableStatuses = allowedTransitions[button.dataset.status] || [button.dataset.status];
    Array.from(statusSelect.options).forEach((option) => { option.hidden = !availableStatuses.includes(option.value); });
    statusError.textContent = '';
    console.log('Opening status modal for lead ID:', statusLeadId, 'with current status:', button.dataset.status);
    statusError.classList.remove('d-block');
    document.getElementById('status-user-email').textContent = button.dataset.email;
    statusModal.classList.remove('d-none');
    statusModal.setAttribute('aria-hidden', 'false');
    statusSelect.focus();
}

function closeStatusModal() {
    statusModal.classList.add('d-none');
    statusModal.setAttribute('aria-hidden', 'true');
    statusLeadId = null;
}

document.querySelectorAll('.status-user').forEach((button) => { button.addEventListener('click', () => openStatusModal(button)); });
document.getElementById('close-status-modal').addEventListener('click', closeStatusModal);
document.getElementById('cancel-status-modal').addEventListener('click', closeStatusModal);

statusForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    statusError.textContent = '';
    saveStatusButton.disabled = true;

    try {
        const response = await fetch(`/leads/${statusLeadId}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ change_status: statusSelect.value }),
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Unable to update lead status.');
        const button = document.querySelector(`.status-user[data-id="${statusLeadId}"]`);
        button.dataset.status = data.status;
        button.previousElementSibling.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
        closeStatusModal();
    } catch (error) {
        statusError.textContent = error.message;
        statusError.classList.add('d-block');
    } finally {
        saveStatusButton.disabled = false;
    }
});
</script>
@endsection
