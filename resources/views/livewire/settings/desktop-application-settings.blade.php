<div>
    <div class="mx-4 p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-3 pt-4 px-5 border-b">
                    <h4 class="card-title text-lg font-semibold mb-1">@lang('superadmin.desktopApplicationSettings')</h4>
                    <p class="card-text text-sm text-gray-500">@lang('superadmin.desktopApplicationSettingsDescription')</p>
                    <p class="mt-2 text-xs text-blue-700 dark:text-blue-300 font-medium">@lang('superadmin.desktopApplicationSettingsDescription2')</p>
                    <div class="mt-3 p-3 bg-yellow-100 border-l-4 border-yellow-400 text-yellow-800 rounded">
                        <p>@lang('superadmin.desktopApplicationSettingsNote')</p>
                    </div>
                </div>
                <div class="card-body p-5">

                    <!-- Windows Application URL -->
                    <div class="mb-6 p-4 border rounded bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center mb-3">
                            <svg class="w-8 h-8 text-white mr-3" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <rect width="512" height="512" rx="64" fill="#0078D6"/>
                                <rect x="48"  y="96" width="192" height="144" fill="#FFFFFF"/>
                                <rect x="272" y="96" width="192" height="144" fill="#FFFFFF"/>
                                <rect x="48"  y="272" width="192" height="144" fill="#FFFFFF"/>
                                <rect x="272" y="272" width="192" height="144" fill="#FFFFFF"/>
                            </svg>
                            <h5 class="text-base font-semibold text-gray-900 dark:text-white">@lang('superadmin.windowsApplication')</h5>
                        </div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('superadmin.downloadUrl')</label>
                        <div class="relative">
                            <input type="url" wire:model="windows_file_path"
                                   class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   placeholder="https://example.com/windows-app.exe">
                            @if(!empty($windows_file_path))
                                <button type="button" wire:click="$set('windows_file_path', '')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        @error('windows_file_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="flex gap-2 mt-3">
                            @if($windows_file_path !== \App\Models\DesktopApplication::WINDOWS_FILE_PATH)
                                <button type="button" wire:click="resetWindowsUrl" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">@lang('superadmin.resetToDefault')</button>
                            @endif
                            @if(!empty($windows_file_path))
                                <a href="{{ $windows_file_path }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    @lang('superadmin.downloadNow')
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Mac Intel Application URL -->
                    <div class="mb-6 p-4 border rounded bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center mb-3">
                            <svg class="w-8 h-8 text-white mr-3" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M339 70c17-20 29-49 26-70-24 1-52 16-68 36-15 18-28 47-24 74 26 2 53-15 66-40zM352 128c-32-2-61 18-79 18-19 0-50-18-83-18-64 0-134 58-134 162 0 140 108 221 134 221 22 0 34-15 66-15 31 0 41 15 66 15 27 0 69-32 96-78-90-35-84-159 4-192-24-34-59-54-95-54z" fill="#000000"/>
                            </svg>
                            <h5 class="text-base font-semibold text-gray-900 dark:text-white">@lang('superadmin.macIntelApplication')</h5>
                        </div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('superadmin.downloadUrl') (Intel)</label>
                        <div class="relative">
                            <input type="url" wire:model="mac_intel_file_path"
                                   class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded text-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   placeholder="https://example.com/mac-intel-app.dmg">
                            @if(!empty($mac_intel_file_path))
                                <button type="button" wire:click="$set('mac_intel_file_path', '')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        @error('mac_intel_file_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="flex gap-2 mt-3">
                            @if($mac_intel_file_path !== \App\Models\DesktopApplication::MAC_INTEL_FILE_PATH)
                                <button type="button" wire:click="resetMacIntelUrl" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">@lang('superadmin.resetToDefault')</button>
                            @endif
                            @if(!empty($mac_intel_file_path))
                                <a href="{{ $mac_intel_file_path }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    @lang('superadmin.downloadNow')
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Mac Silicon Application URL -->
                    <div class="mb-6 p-4 border rounded bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center mb-3">
                            <svg class="w-8 h-8 text-white mr-3" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M339 70c17-20 29-49 26-70-24 1-52 16-68 36-15 18-28 47-24 74 26 2 53-15 66-40zM352 128c-32-2-61 18-79 18-19 0-50-18-83-18-64 0-134 58-134 162 0 140 108 221 134 221 22 0 34-15 66-15 31 0 41 15 66 15 27 0 69-32 96-78-90-35-84-159 4-192-24-34-59-54-95-54z" fill="#000000"/>
                            </svg>
                            <h5 class="text-base font-semibold text-gray-900 dark:text-white">@lang('superadmin.macSiliconApplication')</h5>
                        </div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('superadmin.downloadUrl') (Apple Silicon)</label>
                        <div class="relative">
                            <input type="url" wire:model="mac_silicon_file_path"
                                   class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded text-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   placeholder="https://example.com/mac-silicon-app.dmg">
                            @if(!empty($mac_silicon_file_path))
                                <button type="button" wire:click="$set('mac_silicon_file_path', '')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        @error('mac_silicon_file_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="flex gap-2 mt-3">
                            @if($mac_silicon_file_path !== \App\Models\DesktopApplication::MAC_SILICON_FILE_PATH)
                                <button type="button" wire:click="resetMacSiliconUrl" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">@lang('superadmin.resetToDefault')</button>
                                @endif
                            @if(!empty($mac_silicon_file_path))
                                <a href="{{ $mac_silicon_file_path }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    @lang('superadmin.downloadNow')
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Linux Application URL -->
                    <div class="mb-6 p-4 border rounded bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center mb-3">
                            <svg class="w-8 h-8 text-white mr-3" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M256 40c-58 0-106 48-106 108 0 55 44 175 106 175s106-120 106-175C362 88 314 40 256 40z"
                                      fill="#000000"/>
                                <circle cx="220" cy="148" r="26" fill="#FFFFFF"/>
                                <circle cx="292" cy="148" r="26" fill="#FFFFFF"/>
                                <circle cx="220" cy="148" r="12" fill="#000000"/>
                                <circle cx="292" cy="148" r="12" fill="#000000"/>
                                <path d="M256 352c-72 0-144 36-144 108h288c0-72-72-108-144-108z"
                                      fill="#FDB813"/>
                            </svg>
                            <h5 class="text-base font-semibold text-gray-900 dark:text-white">@lang('superadmin.linuxApplication')</h5>
                        </div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('superadmin.downloadUrl')</label>
                        <div class="relative">
                            <input type="url" wire:model="linux_file_path"
                                   class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded text-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   placeholder="https://example.com/linux-app.AppImage">
                            @if(!empty($linux_file_path))
                                <button type="button" wire:click="$set('linux_file_path', '')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        @error('linux_file_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="flex gap-2 mt-3">
                            @if($linux_file_path !== \App\Models\DesktopApplication::LINUX_FILE_PATH)
                                <button type="button" wire:click="resetLinuxUrl" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">@lang('superadmin.resetToDefault')</button>
                            @endif
                            @if(!empty($linux_file_path))
                                <a href="{{ $linux_file_path }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    @lang('superadmin.downloadNow')
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Single Save Button -->
                    <div class="flex justify-end mt-6">
                        <button type="button" wire:click="saveAll"
                                class="relative px-6 py-2 bg-blue-600 text-sm text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="saveAll">
                            <span wire:loading.remove wire:target="saveAll">@lang('superadmin.saveAllSettings')</span>
                            <span wire:loading wire:target="saveAll" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                @lang('superadmin.saving')
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
