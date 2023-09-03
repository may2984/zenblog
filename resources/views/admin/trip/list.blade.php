@push('css')
<style type="text/css">
  .bi-trash3:hover,
  .bi-pen-fill:hover {
    cursor: pointer
  }

  .error_text {
    height: 7px;
  }
</style>
<link href='//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css' rel='stylesheet'>
@endpush
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

<x-admin.layout>
  <x-slot:title>Add {{$label}}</x-slot:title>
  <section class="section">
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Members</th>
                  <th scope="col">Created At</th>
                </tr>
              </thead>
              <tbody>
                @foreach($data AS $trip)
                    <tr>
                      <th scope="row">{{ $trip->id }}</th>
                      <td>{{ $trip->name }}</td>
                      <td>{!! $trip->members->pluck('name')->join('<br>') !!}</td>
                      <td>{!! $trip->members->pluck('created_at') !!}</td>
                    </tr>                
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-admin.layout>