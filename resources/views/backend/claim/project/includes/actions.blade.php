<x-utils.view-button :href="route('admin.claim.project.show', $project)" />
@if($project->created_by == auth()->user()->id)
	<x-utils.edit-button :href="route('admin.claim.project.edit', $project)" />
	<x-utils.delete-button :href="route('admin.claim.project.destroy', $project)" />
@endif