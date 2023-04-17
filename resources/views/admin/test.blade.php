<x-admin.layout>
    <x-slot:title>ddd</x-slot:title>    
    <div class="container row">
        @foreach($names as $name)
        <div>
          {{ $name }}
        </div>
        @endforeach                             
    </div>    
</x-admin.layout>