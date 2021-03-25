@inject('model', '\App\Domains\Auth\Models\User')

@extends('backend.layouts.app')

@section('title', __('Update User'))

@section('content')
    <h2 class="page-main-title">UPDATE USER</h2>
    
    <x-forms.patch :action="route('admin.auth.user.update', $user)">
        <x-backend.card>
            <x-slot name="header">
                Update user with below information
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.auth.user.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div x-data="{userType : '{{ $user->type }}'}">
                    @if (!$user->isMasterAdmin())
                        <div class="form-group row d-none">
                            <label for="name" class="col-md-2 col-form-label">@lang('Type')</label>

                            <div class="col-md-10">
                                <select name="type" class="form-control" required x-on:change="userType = $event.target.value">
                                    <option value="{{ $model::TYPE_USER }}" {{ $user->type === $model::TYPE_USER ? 'selected' : 'selected' }}>@lang('User')</option>
                                    <option value="{{ $model::TYPE_ADMIN }}" {{ $user->type === $model::TYPE_ADMIN ? 'selected' : '' }}>@lang('Administrator')</option>
                                </select>
                            </div>
                        </div><!--form-group-->
                    @endif

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="organisation_id" class="col-form-label">@lang('Organisation')</label>
                                {{ 
                                    html()->select('organisation_id', $organisations, old('organisation_id') ?? $user->organisation_id)
                                    ->class('form-control select2')
                                    ->required()
                                 }}
                            </div><!--form-group-->
                        </div>
                    </div>

                    <div class="row" style="{{ (in_array(current_user_role(), ['Administrator', 'Super User', 'Finance Officer', 'Project Admin']) && $user->id == auth()->user()->id) ? 'display: none;' : '' }}">
                        <div class="col">
                            <div class="form-group">
                                <label for="pools[]">Colleges</label>
                                {{ html()->multiselect('pools[]', $pools, $user->pools->pluck('id'))
                                    ->class('form-control select2')
                                    ->required()
                                 }}
                             </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="first_name" class="col-form-label">@lang('First Name')</label>
                                <input type="text" name="first_name" class="form-control" placeholder="{{ __('First Name') }}" value="{{ old('first_name') ?? $user->first_name }}" maxlength="100" required />
                            </div><!--form-group-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="last_name" class="col-form-label">@lang('Surname')</label>
                                <input type="text" name="last_name" class="form-control" placeholder="{{ __('Last Name') }}" value="{{ old('last_name') ?? $user->last_name }}" maxlength="100" required />
                            </div><!--form-group-->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="job_title" class="col-form-label">@lang('Job Title')</label>
                                <input type="text" name="job_title" class="form-control" placeholder="{{ __('Job Title') }}" value="{{ old('job_title') ?? $user->job_title }}" maxlength="100" required />
                            </div><!--form-group-->
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="email" class="col-form-label">@lang('E-mail Address')</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') ?? $user->email }}" maxlength="255" required />
                            </div><!--form-group-->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="department" class="col-form-label">@lang('Department')</label>
                                <input type="text" name="department" class="form-control" placeholder="{{ __('Department') }}" value="{{ old('department') ?? $user->department }}" maxlength="100" required />
                            </div><!--form-group-->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direct_dial">Direct Dial</label>
                                {{ html()->text('direct_dial', (!empty($correspondenceAddress)) ? $correspondenceAddress->direct_dial ?? '' : '')
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                {{ html()->text('mobile', (!empty($correspondenceAddress)) ? $correspondenceAddress->mobile ?? '' : '')
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <hr>
                            <h6>CORRESPONDENCE ADDRESS:</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="building_name_no">Building Name/No</label>
                                {{ html()->text('building_name_no', (!empty($correspondenceAddress)) ? $correspondenceAddress->building_name_no ?? '' : '' )
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="street">Address Line 1</label>
                                {{ html()->text('street', (!empty($correspondenceAddress)) ? $correspondenceAddress->street ?? '' : '')
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="address_line_2">Address Line 2</label>
                                {{ html()->text('address_line_2', (!empty($correspondenceAddress)) ? $correspondenceAddress->address_line_2 ?? '' : '')
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="county">County</label>
                                {{ html()->text('county', (!empty($correspondenceAddress)) ? $correspondenceAddress->county ?? '' : '')
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="city">City</label>
                                {{ html()->text('city', (!empty($correspondenceAddress)) ? $correspondenceAddress->city ?? '' : '')
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="postcode">Post Code</label>
                                {{ html()->text('postcode', (!empty($correspondenceAddress)) ? $correspondenceAddress->postcode ?? '' : '')
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="correspending_email">Email</label>
                                {{ html()->text('correspending_email', (!empty($correspondenceAddress)) ? $correspondenceAddress->email ?? '' : '')
                                    ->class('form-control')
                                    ->required()
                                 }}
                            </div>
                        </div>
                    </div>

                    @if (!$user->isMasterAdmin())
                        @include('backend.auth.includes.roles')

                        @if (!config('boilerplate.access.user.only_roles') && 1==2)
                            @include('backend.auth.includes.permissions')
                        @endif
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update User')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection

@push('after-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("input[name='roles[]']").on("change", function() {
                if($(this).prop('checked')) {
                   $("input[name='roles[]']").each(function() {
                        $(this).prop('checked', false);
                   });
                }
                $(this).prop('checked', true);
            });
            $("#organisation_id").on("change", function() {
                var organisation_id = $(this).val();
                var url = '{{ url("admin/system/organisation") }}/';
                $.ajax({
                    url : url + organisation_id,
                    type : 'GET',
                    success : function(response) {
                        $("#building_name_no").val(response.building_name_no);
                        $("#street").val(response.street);
                        $("#address_line_2").val(response.address_line_2);
                        $("#county").val(response.county);
                        $("#city").val(response.city);
                        $("#postcode").val(response.postcode);
                    },
                    error : function(error) {
                        console.log(error);  
                    }
                })
            });
        });
    </script>
@endpush
