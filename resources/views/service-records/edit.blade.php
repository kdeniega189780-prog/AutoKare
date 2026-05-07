<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-white">{{ __('Update service record') }}</h1>
    </x-slot>

    <p class="mb-5 text-sm text-slate-100/70">
        {{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }} — {{ $schedule->task_description }}
    </p>

    <div class="max-w-3xl">
        <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm p-6">
            <form method="POST" action="{{ route('service-records.update', $schedule) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="parts_used" :value="__('Parts used')" />
                    <textarea id="parts_used" name="parts_used" rows="3"
                              class="mt-2 block w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-200/50 focus:border-emerald-400/60 focus:ring-2 focus:ring-emerald-400/30">{{ old('parts_used', $record->parts_used) }}</textarea>
                    <x-input-error :messages="$errors->get('parts_used')" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <x-input-label for="labor_hours" :value="__('Labor hours')" />
                        <div class="mt-2">
                            <x-text-input id="labor_hours" name="labor_hours" type="number" step="0.01" min="0" :value="old('labor_hours', $record->labor_hours)" />
                        </div>
                        <x-input-error :messages="$errors->get('labor_hours')" />
                    </div>
                    <div>
                        <x-input-label for="labor_cost" :value="__('Labor cost (PHP)')" />
                        <div class="mt-2">
                            <x-text-input id="labor_cost" name="labor_cost" type="number" step="0.01" min="0" :value="old('labor_cost', $record->labor_cost)" />
                        </div>
                        <x-input-error :messages="$errors->get('labor_cost')" />
                    </div>
                    <div>
                        <x-input-label for="parts_cost" :value="__('Parts cost (PHP)')" />
                        <div class="mt-2">
                            <x-text-input id="parts_cost" name="parts_cost" type="number" step="0.01" min="0" :value="old('parts_cost', $record->parts_cost)" />
                        </div>
                        <x-input-error :messages="$errors->get('parts_cost')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="notes" :value="__('Technician notes')" />
                    <textarea id="notes" name="notes" rows="3"
                              class="mt-2 block w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-200/50 focus:border-emerald-400/60 focus:ring-2 focus:ring-emerald-400/30">{{ old('notes', $record->notes) }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" />
                </div>

                <div class="pt-1">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-100/80">
                        <input type="hidden" name="mark_completed" value="0">
                        <input type="checkbox" name="mark_completed" value="1"
                               class="h-4 w-4 rounded border-white/20 bg-white/10 text-emerald-600 focus:ring-emerald-400/30"
                               id="mark_completed" @checked(old('mark_completed'))>
                        <span>{{ __('Mark job completed (sets schedule to completed)') }}</span>
                    </label>
                </div>

                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <x-primary-button>{{ __('Save record') }}</x-primary-button>
                    <a href="{{ route('schedules.show', $schedule) }}" class="text-sm font-semibold text-slate-100/70 hover:text-white underline underline-offset-4">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
