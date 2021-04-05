<x-utils.view-button :href="route('admin.claim.project.show', $project)" />
@if(auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Developer') || (auth()->user()->hasRole('Super User') && $project->userHasPartialAccessToProject()) || (!empty(projectLead($project)) && (projectLead($project)->id == auth()->user()->id)))
	<!-- <x-utils.edit-button :href="route('admin.claim.project.edit', $project)" /> -->
	@if(auth()->user()->hasRole('Developer') || auth()->user()->hasRole('Administrator'))
	<x-utils.delete-button :href="route('admin.claim.project.destroy', $project)" />
	@endif
@endif