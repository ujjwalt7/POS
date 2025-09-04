<?php

namespace App\Livewire\Settings;

use App\Models\Module;
use App\Models\Role;
use App\Models\Restaurant;
use Livewire\Component;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RoleSettings extends Component
{
    use LivewireAlert;

    public $permissions;
    public $roles;

    // New role creation properties
    public $showAddRoleModal = false;
    public $newRoleDisplayName = '';
    public $copyFromRole = '';

    
    public function mount()
    {
        $this->permissions = Module::with(['permissions.roles'])->get();
        $this->roles = Role::where('display_name', '<>', 'Admin')
                          ->where('display_name', '<>', 'Super Admin')
                          ->where('restaurant_id', restaurant()->id)
                          ->get();
    }

    public function setPermission($roleID, $permissionID)
    {
        $role = Role::find($roleID);
        $role->givePermissionTo($permissionID);
    }

    public function removePermission($roleID, $permissionID)
    {
        $role = Role::find($roleID);
        $role->revokePermissionTo($permissionID);
    }

    public function openAddRoleModal()
    {
        $this->showAddRoleModal = true;
        $this->resetRoleForm();
    }

    public function showAddRole()
    {
        $this->showAddRoleModal = true;
        $this->resetRoleForm();
    }

    public function closeAddRoleModal()
    {
        $this->showAddRoleModal = false;
        $this->resetRoleForm();
    }

    public function updatedShowAddRoleModal($value)
    {
        if (!$value) {
            $this->resetRoleForm();
        }
    }

    public function resetRoleForm()
    {
        $this->newRoleDisplayName = '';
        $this->copyFromRole = '';
        $this->resetErrorBag();
    }



    public function createRole()
    {
        $this->validate([
            'newRoleDisplayName' => 'required|string|max:255',
        ]);

        // Check if role display name already exists for any restaurant
        $existingRole = Role::where('display_name', $this->newRoleDisplayName)
                           ->where('display_name', '<>', 'Admin')
                           ->where('display_name', '<>', 'Super Admin')
                           ->first();

        if ($existingRole) {
            $this->addError('newRoleDisplayName', 'Role display name already exists.');
            return;
        }

        try {
            // Get all restaurants
            $restaurants = Restaurant::all();

            // Get source role permissions if copying
            $sourcePermissions = null;
            if ($this->copyFromRole) {
                $copyRole = Role::find($this->copyFromRole);
                if ($copyRole) {
                    $sourcePermissions = $copyRole->permissions;
                }
            }

            // Create the role for each restaurant
            foreach ($restaurants as $restaurant) {
                $role = Role::create([
                    'name' => $this->newRoleDisplayName . '_' . $restaurant->id,
                    'display_name' => $this->newRoleDisplayName,
                    'guard_name' => 'web',
                    'restaurant_id' => $restaurant->id,
                ]);

                // Copy permissions to this restaurant's role if source permissions exist
                if ($sourcePermissions) {
                    $role->syncPermissions($sourcePermissions);
                }
            }

            // Refresh the roles list for current restaurant
            $this->roles = Role::where('display_name', '<>', 'Admin')
                              ->where('display_name', '<>', 'Super Admin')
                              ->where('restaurant_id', restaurant()->id)
                              ->get();

                                    // Show success message
            $this->alert('success', __('app.roleCreatedForAllRestaurants'), [
                'toast' => true,
                'position' => 'top-end',
                'showCancelButton' => false,
                'cancelButtonText' => __('app.close')
            ]);

            // Close modal and reset form
            $this->showAddRoleModal = false;
            $this->resetRoleForm();

        } catch (\Exception $e) {
            $this->alert('error', 'Error creating role: ' . $e->getMessage(), [
                'toast' => true,
                'position' => 'top-end',
                'showCancelButton' => false,
                'cancelButtonText' => __('app.close')
            ]);
        }
    }

    public function render()
    {
        return view('livewire.settings.role-settings');
    }

}
