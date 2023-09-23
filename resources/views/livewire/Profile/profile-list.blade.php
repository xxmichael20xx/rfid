<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">List of profiles</h1>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="app-card app-card-chart h-100 shadow-sm">
                <div class="app-card-body p-3 p-lg-4">
                    <div class="table-responsive">
                        <table class="table app-table-hover mb-0 text-left visitors-table">
                            <thead class="bg-portal-green">
                                <tr>
                                    <th class="cell">Id</th>
                                    <th class="cell">Home Owner</th>
                                    <th class="cell">Name</th>
                                    <th class="cell">Date of Birth</th>
                                    <th class="cell">Contact No</th>
                                    <th class="cell">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($profiles as $profile)
                                    <tr>
                                        <td class="cell">{{ $profile->id }}</td>
                                        <td class="cell">{{ $profile->homeOwner->full_name }}</td>
                                        <td class="cell">{{ $profile->full_name }}</td>
                                        <td class="cell">{{ \Carbon\Carbon::parse($profile->date_of_borth)->format('M d, Y') }}</td>
                                        <td class="cell">{{ $profile->contact_no ?? 'No contact number' }}</td>
                                        <td class="cell d-flex">
                                            @livewire('profile.profile-view', ['profileId' => $profile->id])
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="cell text-center" colspan="5">No result(s)</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
