<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-white">{{ __('Schedule maintenance') }}</h1>
    </x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm p-6">
            <form method="POST" action="{{ route('schedules.store') }}" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="vehicle_id" :value="__('Vehicle')" />
                    <select id="vehicle_id" name="vehicle_id" required
                            class="mt-2 block w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white focus:border-emerald-400/60 focus:ring-2 focus:ring-emerald-400/30">
                        <option value="">{{ __('Select vehicle…') }}</option>
                        @foreach ($vehicles as $v)
                            <option value="{{ $v->id }}" @selected(old('vehicle_id', $selectedVehicleId) == $v->id)>
                                {{ $v->make }} {{ $v->model }} ({{ $v->license_plate }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('vehicle_id')" />
                </div>

                <div>
                    <x-input-label for="mechanic_id" :value="__('Assign mechanic (optional)')" />
                    <select id="mechanic_id" name="mechanic_id"
                            class="mt-2 block w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white focus:border-emerald-400/60 focus:ring-2 focus:ring-emerald-400/30">
                        <option value="">{{ __('Auto / admin assigns later') }}</option>
                        @foreach ($mechanics as $m)
                            <option value="{{ $m->id }}" @selected(old('mechanic_id') == $m->id)>{{ $m->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('mechanic_id')" />
                </div>

                <div>
                    <x-input-label for="scheduled_at" :value="__('Date & time')" />
                    <div class="mt-2">
                        <x-text-input id="scheduled_at" name="scheduled_at" type="datetime-local" :value="old('scheduled_at')" required />
                    </div>
                    <x-input-error :messages="$errors->get('scheduled_at')" />
                </div>

                <div>
                    <x-input-label for="task_description" :value="__('Task description')" />
                    <textarea id="task_description" name="task_description" rows="3" required
                              class="mt-2 block w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-200/50 focus:border-emerald-400/60 focus:ring-2 focus:ring-emerald-400/30">{{ old('task_description') }}</textarea>
                    <x-input-error :messages="$errors->get('task_description')" />
                </div>

                @if (Auth::user()->isAdmin())
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status"
                                class="mt-2 block w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white focus:border-emerald-400/60 focus:ring-2 focus:ring-emerald-400/30">
                            @foreach (['pending', 'in_progress', 'completed', 'cancelled'] as $st)
                                <option value="{{ $st }}" @selected(old('status', 'pending') === $st)>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" />
                    </div>
                @endif

                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    <a href="{{ route('schedules.index') }}" class="text-sm font-semibold text-slate-100/70 hover:text-white underline underline-offset-4">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
