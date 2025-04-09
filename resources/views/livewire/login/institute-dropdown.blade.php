<div class="relative" wire:click.away="$set('institutes', [])">
    <label for="institute" class="block text-gray-700 text-sm font-medium mb-2">Select Institute</label>

    <input type="text" wire:model.live="search" placeholder="Search institute..."
           class="w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
           autocomplete="off" aria-errormessage="Please select an Institute" required>

    <input type="hidden" name="institute" value="{{($this->selectedInstitute)?$this->selectedInstitute:null}}">

    <!-- Display the list of institutes based on search results -->
    @if (!empty($institutes))
        <ul class="absolute z-10 w-full bg-white border border-gray-200 rounded-md max-h-60 overflow-y-auto mt-1 shadow-md">
            @foreach ($institutes as $institute)
                <li wire:click="selectInstitute({{ $institute->id }})"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    {{ $institute->name }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
