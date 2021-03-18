@inject('model', '\App\Domains\Auth\Models\User')

@extends('backend.layouts.app')

@section('title', __('Create User'))

@section('content')
    <x-forms.post :action="route('admin.auth.user.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create User')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.auth.user.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div x-data="{userType : '{{ $model::TYPE_ADMIN }}'}">
                    <div class="form-group row d-none">
                        <label for="name" class="col-md-2 col-form-label">@lang('Type')</label>

                        <div class="col-md-10">
                            <select name="type" class="form-control" required x-on:change="userType = $event.target.value">
                                <option value="{{ $model::TYPE_USER }}">@lang('User')</option>
                                <option value="{{ $model::TYPE_ADMIN }}" selected>@lang('Administrator')</option>
                            </select>
                        </div>
                    </div><!--form-group-->

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="first_name" class="col-form-label">@lang('First Name')</label>
                                <input type="text" name="first_name" class="form-control" placeholder="{{ __('First Name') }}" value="{{ old('first_name') }}" maxlength="100" required />
                            </div><!--form-group-->
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="last_name" class="col-form-label">@lang('Last Name')</label>
                                <input type="text" name="last_name" class="form-control" placeholder="{{ __('Last Name') }}" value="{{ old('last_name') }}" maxlength="100" required />
                            </div><!--form-group-->
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="job_title" class="col-form-label">@lang('Job Title')</label>
                                <input type="text" name="job_title" class="form-control" placeholder="{{ __('Job Title') }}" value="{{ old('job_title') }}" maxlength="100" required />
                            </div><!--form-group-->
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="department" class="col-form-label">@lang('Department')</label>
                                <input type="text" name="department" class="form-control" placeholder="{{ __('Department') }}" value="{{ old('department') }}" maxlength="100" required />
                            </div><!--form-group-->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="organisation_id">Organisation</label>
                                {{ html()->select('organisation_id', $organisations)
                                    ->class('form-control select2')
                                    ->placeholder('Select Organisation')
                                    ->required()
                                 }}
                             </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="email" class="col-form-label">@lang('E-mail Address')</label>
                                <input type="email" name="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') }}" maxlength="255" required />
                            </div><!--form-group-->
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="password" class="col-form-label">@lang('Password')</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="new-password" />
                            </div><!--form-group-->
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="password_confirmation" class="col-form-label">@lang('Password Confirmation')</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Password Confirmation') }}" maxlength="100" required autocomplete="new-password" />
                            </div><!--form-group-->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="pools[]">Pools</label>
                                {{ html()->multiselect('pools[]', $pools)
                                    ->class('form-control select2')
                                    ->required()
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
                                <label for="building_name_no">Building Name No:</label>
                                {{ html()->text('building_name_no', old('building_name_no'))
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="street">Street</label>
                                {{ html()->text('street', old('street'))
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="address_line_2">Address Line 2</label>
                                {{ html()->text('address_line_2', old('address_line_2'))
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="county">County</label>
                                {{ html()->text('county', old('county'))
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="city">City</label>
                                {{ html()->text('city', old('city'))
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="postcode">Post Code</label>
                                {{ html()->text('postcode', old('postcode'))
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="correspending_email">Email</label>
                                {{ html()->text('correspending_email', old('correspending_email'))
                                    ->class('form-control')
                                    ->required()
                                 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                {{ html()->text('mobile', old('mobile'))
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direct_dial">Direct Dial</label>
                                {{ html()->text('direct_dial', old('direct_dial'))
                                    ->class('form-control')
                                 }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <label for="active" class="col-md-2 col-form-label">@lang('Active')</label>

                        <div class="col-md-10">
                            <div class="form-check">
                                <input name="active" id="active" class="form-check-input" type="checkbox" value="1" {{ old('active', true) ? 'checked' : '' }} />
                            </div><!--form-check-->
                        </div>
                    </div><!--form-group-->

                    <div x-data="{ emailVerified : false }">
                        <div class="form-group row">
                            <label for="email_verified" class="col-md-2 col-form-label">@lang('E-mail Verified')</label>

                            <div class="col-md-10">
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        name="email_verified"
                                        id="email_verified"
                                        value="1"
                                        class="form-check-input"
                                        x-on:click="emailVerified = !emailVerified"
                                        {{ old('email_verified') ? 'checked' : 'checked' }} />
                                </div><!--form-check-->
                            </div>
                        </div><!--form-group-->

                        <div x-show="!emailVerified">
                            <div class="form-group row">
                                <label for="send_confirmation_email" class="col-md-2 col-form-label">@lang('Send Confirmation E-mail')</label>

                                <div class="col-md-10">
                                    <div class="form-check">
                                        <input
                                            type="checkbox"
                                            name="send_confirmation_email"
                                            id="send_confirmation_email"
                                            value="1"
                                            class="form-check-input"
                                            {{ old('send_confirmation_email') ? 'checked' : '' }} />
                                    </div><!--form-check-->
                                </div>
                            </div><!--form-group-->
                        </div>
                    </div>

                    @include('backend.auth.includes.roles')

                    @if (!config('boilerplate.access.user.only_roles') && 1==2)
                        @include('backend.auth.includes.permissions')
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create User')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
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
