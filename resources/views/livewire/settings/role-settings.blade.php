<div>

     <div class="px-4 mb-4">
        <x-button type='button' wire:click="showAddRole" >@lang('app.addNew') @lang('app.role')</x-button>
    </div>
    <div class="overflow-hidden shadow mb-8">
        <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600" wire:key="role-settings-table">
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                <th scope="col"
                class="py-2.5 px-4 text-xs font-medium ltr:text-left rtl:text-right text-gray-500 uppercase dark:text-gray-400">
                    <div class="text-xs font-light">@lang('app.user')</div>
                    <div class="text-sm font-medium">@lang('app.permission')</div>
                </th>

                @foreach ($roles as $item)
                    <th scope="col"
                    class="py-2.5 px-4 text-xs font-medium ltr:text-left rtl:text-right text-gray-500 uppercase dark:text-gray-400">
                        <div class="text-xs font-light">@lang('app.role')</div>
                        <div class="text-sm font-medium">{{ ($item->display_name) }}</div>
                    </th>           
                @endforeach
            </tr>     

        </thead>

        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
            @foreach ($permissions as $module)
                <tr>
                    <td class="bg-gray-50 p-4 text-sm font-semibold ltr:text-left rtl:text-right text-gray-500 uppercase dark:text-gray-400 dark:bg-gray-900" colspan="{{ (count($roles) + 1) }}">
                        {{ __('permissions.modules.'.$module->name) }}
                    </td>
                </tr>
            
                @foreach ($module->permissions as $item)
                
                @php
                    $permissionRoles = $item->roles->pluck('name')->toArray();
                @endphp

                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" wire:key='perms-item-{{ $item->id . microtime() }}'>
                    <td class="flex items-center px-4 py-2 mr-12 space-x-6 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('permissions.permissions.'.$item->name) }}
                    </td>
    
                    @foreach ($roles as $role)
                    <td class="px-4 py-2 mr-12 space-x-6">
                        @if (!in_array($role->name, $permissionRoles))
                            <x-secondary-button-table wire:click='setPermission({{ $role->id }}, {{ $item->id }})'>
                                &plus;
                            </x-secondary-button-table>
                        @else
                            <x-danger-button-table wire:click='removePermission({{ $role->id }}, {{ $item->id }})'>
                                &minus;
                            </x-danger-button-table>
                        @endif
                    </td>                
                    @endforeach
    
                </tr>
                @endforeach
            @endforeach

        </tbody>
       
    </table>
</div>

    <x-right-modal wire:model.live="showAddRoleModal">
        <x-slot name="title">
            {{ __("app.addNew") }} {{ __("app.role") }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="createRole">
                <!-- Display Name -->
                <div class="mb-4">
                    <label for="newRoleDisplayName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('app.displayName') *
                    </label>
                    <input type="text"
                           id="newRoleDisplayName"
                           wire:model="newRoleDisplayName"
                           class="block px-3 py-2 mt-1 w-full rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="@lang('app.enter') @lang('app.displayName')">
                    @error('newRoleDisplayName')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Copy Permissions From Role -->
                <div class="mb-4">
                    <label for="copyFromRole" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('app.copyPermissionsFrom') @lang('app.role') (@lang('app.optional'))
                    </label>
                    <select id="copyFromRole"
                            wire:model="copyFromRole"
                            class="block px-3 py-2 mt-1 w-full rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">@lang('app.dontCopyPermissions')</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ __($role->display_name) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Modal Buttons -->
                <div class="flex justify-end space-x-2">
                    <x-secondary-button wire:click="$set('showAddRoleModal', false)" wire:loading.attr="disabled">
                        {{ __('app.cancel') }}
                    </x-secondary-button>
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('app.create') }} {{ __('app.role') }}
                    </x-button>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <!-- Empty footer slot to match the pattern -->
        </x-slot>
    </x-right-modal>

</div>
