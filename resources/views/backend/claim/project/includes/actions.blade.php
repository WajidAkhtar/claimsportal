<x-utils.view-button :href="route('admin.claim.project.show', $project)" />
@if($project->created_by == auth()->user()->id || auth()->user()->hasRole('Super User'))
	<x-utils.edit-button :href="route('admin.claim.project.edit', $project)" />
	@if(auth()->user()->hasRole('Administrator'))
	<x-utils.delete-button :href="route('admin.claim.project.destroy', $project)" />
	@endif
@endif